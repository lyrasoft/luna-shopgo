<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase;

use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase\Form\EditForm;
use Lyrasoft\ShopGo\Repository\AdditionalPurchaseRepository;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Attributes\JsonApi;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\ORM\ORM;

use function Windwalker\collect;
use function Windwalker\Query\val;

/**
 * The AdditionalPurchaseController class.
 */
#[Controller()]
class AdditionalPurchaseController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        FormFactory $formFactory,
        ORM $orm,
        #[Autowire] AdditionalPurchaseRepository $repository,
    ): mixed {
        $task = $app->input('task');

        if ($task === 'switch_product') {
            $repository->getState()->remember('edit.data', $app->input('item'));

            return $nav->back();
        }

        $productId = $app->input('item')['attach_product_id'] ?? 0;
        $product = $orm->findOne(Product::class, $productId);

        $form = $formFactory->create(EditForm::class, product: $product);

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($app) {
                $data = $event->getData();
                $orm = $event->getORM();

                // Attachments
                $attachmentSet = $app->input('attachments');
                $attachments = [];

                foreach ($attachmentSet as $productId => $variants) {
                    foreach ($variants as $variantId => $attachment) {
                        $attachmentItem = new AdditionalPurchaseAttachment();

                        $attachmentItem->setId((int) ($attachment['id'] ?? 0));
                        $attachmentItem->setAdditionalPurchaseId((int) $data['id']);
                        $attachmentItem->setProductId((int) $productId);
                        $attachmentItem->setVariantId((int) $variantId);
                        $attachmentItem->setMethod($attachment['method']);
                        $attachmentItem->setPrice((float) $attachment['price']);
                        $attachmentItem->setMaxQuantity((int) $attachment['max_quantity']);
                        $attachmentItem->setState((int) $attachment['state']);
                        $attachmentItem->setOrdering(1);

                        $attachments[] = $attachmentItem;
                    }
                }

                $orm->sync(
                    AdditionalPurchaseAttachment::class,
                    $attachments,
                    ['additional_purchase_id' => $data['id']],
                    ['id']
                );

                // Targets
                $productIds = $app->input('item')['products'];

                $maps = [];

                foreach ($productIds as $productId) {
                    $map = new AdditionalPurchaseTarget();
                    $map->setAdditionalPurchaseId((int) $data['id']);
                    $map->setProductId((int) $productId);

                    $maps[] = $map;
                }

                $orm->flush(
                    AdditionalPurchaseTarget::class,
                    $maps,
                    ['additional_purchase_id' => $data['id']],
                );
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('additional_purchase_list');

            case 'save2new':
                return $nav->to('additional_purchase_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
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
        #[Autowire] AdditionalPurchaseRepository $repository,
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

    public function getProductInfo(AppContext $app, ORM $orm): array
    {
        $id = $app->input('id');

        /** @var Product $product */
        $product = $orm->mustFindOne(Product::class, $id);
        $variant = $orm->mustFindOne(ProductVariant::class, ['product_id' => $id, 'primary' => 1]);

        $product->variant = $variant;

        $variants = $orm->from(ProductVariant::class, 'variant')
            ->where('product_id', $product->getId())
            ->all(ProductVariant::class);

        $variants = $variants->filter(
            function (ProductVariant $variant) use ($product) {
                if ($product->getVariants() === 0) {
                    return $variant->isPrimary();
                }

                return !$variant->isPrimary();
            }
        )->values();

        return compact('product', 'variants');
    }
}
