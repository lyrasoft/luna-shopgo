<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Product;

use Lyrasoft\ShopGo\Data\ListOptionCollection;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\ProductFeature;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Lyrasoft\ShopGo\Module\Admin\Product\Form\EditForm;
use Lyrasoft\ShopGo\Repository\ProductRepository;
use Lyrasoft\ShopGo\Service\VariantService;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Attributes\JsonApi;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\ORM\ORM;

use function Windwalker\collect;

/**
 * The ProductController class.
 */
#[Controller()]
class ProductController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ProductRepository $repository,
    ): mixed {
        $form = $app->make(EditForm::class);

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($repository, $app) {
                $orm = $event->getORM();
                $data = $event->getData();

                // Save Categories
                $this->saveCategories($app, $orm, $data);

                $searchIndexes = [];

                $variantData = $app->input('item')['variant'];

                // MainVariant
                $mainVariant = $orm->findOneOrCreate(
                    ProductVariant::class,
                    ['product_id' => $data['id'], 'primary' => 1]
                );

                $mainVariant = $orm->hydrateEntity($variantData, $mainVariant);

                $searchIndexes[] = $mainVariant->getSearchIndex();

                $orm->updateOne(ProductVariant::class, $mainVariant);

                // Sub Variants
                $variants = $this->saveSubVariants($app, $orm, (int) $data['id']);

                foreach ($variants as $variant) {
                    $searchIndexes[] = $variant->getSearchIndex();
                }

                // Save Discounts
                $this->saveDiscounts($app, $orm, (int) $data['id']);

                // Save variant info
                $data['variants'] = count($variants);
                $data['primary_variant_id'] = $mainVariant->getId();
                $data['search_index'] = implode('|', array_filter($searchIndexes));

                $repository->save($data);
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('product_list');

            case 'save2new':
                return $nav->to('product_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);

                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    /**
     * @param  AppContext  $app
     * @param  ORM         $orm
     * @param  array       $data
     *
     * @return  array<ShopCategoryMap>
     */
    protected function saveCategories(AppContext $app, ORM $orm, array $data): array
    {
        // Primary
        $map = new ShopCategoryMap();
        $map->setType('product');
        $map->setTargetId((int) $data['id']);
        $map->setCategoryId($data['category_id']);
        $map->setPrimary(true);

        $maps[] = $map;

        // Sub
        $categories = $app->input('item')['sub_categories'];

        foreach ($categories as $categoryId) {
            $map = new ShopCategoryMap();
            $map->setType('product');
            $map->setTargetId((int) $data['id']);
            $map->setCategoryId((int) $categoryId);
            $map->setPrimary(false);

            $maps[] = $map;
        }

        $orm->sync(
            ShopCategoryMap::class,
            $maps,
            ['type' => 'product', 'target_id' => $data['id']],
            ['type', 'category_id']
        );

        return $maps;
    }

    /**
     * @param  AppContext  $app
     * @param  ORM         $orm
     * @param  int         $productId
     *
     * @return  Collection<ProductVariant>
     *
     * @throws \JsonException
     * @throws \Windwalker\DI\Exception\DefinitionException
     */
    protected function saveSubVariants(AppContext $app, ORM $orm, int $productId): Collection
    {
        $variants = $app->input('variants') ?: throw new \RuntimeException('No variants data');
        $chronosService = $app->service(ChronosService::class);

        $variants = collect(
            json_decode($variants, true, 512, JSON_THROW_ON_ERROR)
        );

        $variants = $variants->map(function ($variant) use ($chronosService, $orm) {
            $variant = $orm->toEntity(ProductVariant::class, $variant);

            $variant->setPublishUp(
                $chronosService->toServerFormat($variant->getPublishUp())
            );

            $variant->setPublishDown(
                $chronosService->toServerFormat($variant->getPublishDown())
            );

            return $variant;
        });

        $orm->sync(
            ProductVariant::class,
            $variants,
            ['product_id' => $productId, 'primary' => 0],
            ['id']
        );

        return $variants;
    }

    protected function saveDiscounts(AppContext $app, ORM $orm, int $productId): void
    {
        $discounts = $app->input('discounts');
        $chronosService = $app->service(ChronosService::class);

        $discounts = collect(
            json_decode($discounts, true, 512, JSON_THROW_ON_ERROR)
        );

        $discounts = $discounts->map(function ($discount) use ($chronosService, $orm) {
            $discount = $orm->toEntity(Discount::class, $discount);

            $discount->setPublishUp(
                $chronosService->toServerFormat($discount->getPublishUp())
            );

            $discount->setPublishDown(
                $chronosService->toServerFormat($discount->getPublishDown())
            );

            return $discount;
        });

        $orm->sync(
            Discount::class,
            $discounts,
            ['product_id' => $productId, 'type' => DiscountType::PRODUCT()],
            ['id']
        );
    }

    public function delete(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
        GridController $controller
    ): mixed {
        $task = $app->input('task');
        $data = match ($task) {
            'publish' => ['state' => 1],
            'unpublish' => ['state' => 0],
            default => null
        };

        return $app->call([$controller, 'batch'], compact('repository', 'data'));
    }

    public function copy(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }

    #[JsonApi]
    public function ajax(AppContext $app): mixed
    {
        $task = $app->input('task');

        return $app->call([$this, $task]);
    }

    public function getFeatureOptions(ORM $orm): Collection
    {
        $features = $orm->from(ProductFeature::class)
            ->where('state', 1)
            ->all(ProductFeature::class);

        /** @var ProductFeature $feature */
        foreach ($features as $feature) {
            /** @var ListOptionCollection $options */
            $options = $feature->getOptions();

            foreach ($options as $i => $option) {
                $option->setParentId($feature->getId());

                $options[$i] = $option;
            }
        }

        return $features;
    }

    /**
     * @param  AppContext  $app
     * @param  ORM  $orm
     * @param  VariantService  $variantService
     *
     * @return  array<ProductVariant>
     */
    public function generateVariants(AppContext $app, ORM $orm, #[Autowire] VariantService $variantService): array
    {
        $productId = $app->input('product_id');
        $featureOptionGroup = $app->input('options') ?? [];
        $currentHashes = (array) ($app->input('currentHashes') ?? []);

        $featureOptionGroup = array_filter($featureOptionGroup, static fn($options) => $options !== []);

        $optionGroups = $variantService->sortOptionsGroup($featureOptionGroup);

        $variants = [];

        foreach ($optionGroups as $optionGroup) {
            usort(
                $optionGroup,
                static fn($a, $b) => strcmp($a['value'], $b['value'])
            );

            $uids = array_map(static fn($option) => $option['uid'], $optionGroup);
            $texts = array_map(static fn($option) => $option['text'], $optionGroup);

            $hash = $variantService::hash($uids);

            if (in_array($hash, $currentHashes, true)) {
                continue;
            }

            $variant = new ProductVariant();
            $variant->setProductId($productId);
            $variant->setHash($hash);
            $variant->setTitle(implode(' / ', $texts));
            // $variant->model = $product->model === ''
            //     ? $product->model
            //     : $product->model . '-' . implode('-', $optionGroup);
            $variant->getDimension(); // Pre-create ValueObject
            $variant->setSubtract(true);
            $variant->setState(1);
            $variant->setOptions($optionGroup);

            $variants[] = $variant;
        }

        return $variants;
    }
}
