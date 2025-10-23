<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Product;

use Lyrasoft\Favorite\Entity\Favorite;
use Lyrasoft\Favorite\Service\FavoriteService;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Entity\Tag;
use Lyrasoft\Luna\Entity\TagMap;
use Lyrasoft\Luna\PageBuilder\PageService;
use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductAttribute;
use Lyrasoft\ShopGo\Entity\ProductTab;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Lyrasoft\ShopGo\Repository\ProductRepository;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
use Lyrasoft\ShopGo\Service\ProductAttributeService;
use Lyrasoft\ShopGo\Service\VariantService;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Psr\Cache\InvalidArgumentException;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Http\Browser;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Exception\RouteNotFoundException;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\DI\Exception\DefinitionException;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

use function Windwalker\collect;
use function Windwalker\Query\val;
use function Windwalker\str;

/**
 * The ProductItemView class.
 */
#[ViewModel(
    layout: 'product-item',
    js: 'product-item.js'
)]
class ProductItemView implements ViewModelInterface
{
    use TranslatorTrait;
    use CurrencyAwareTrait;

    /**
     * Constructor.
     */
    public function __construct(
        protected ORM $orm,
        protected Navigator $nav,
        protected PageService $pageService,
        #[Autowire]
        protected ProductRepository $repository,
        protected UserService $userService,
        protected VariantService $variantService,
        protected AdditionalPurchaseService $additionalPurchaseService,
        protected ProductAttributeService $productAttributeService,
        protected FavoriteService $favoriteService,
    ) {
        //
    }

    /**
     * Prepare View.
     *
     * @param  AppContext  $app  The web app context.
     * @param  View        $view  The view object.
     *
     * @return RouteUri|array
     *
     * @throws InvalidArgumentException
     * @throws DefinitionException
     */
    public function prepare(AppContext $app, View $view): RouteUri|array
    {
        [$id, $alias, $previewSecret] = $app->input('id', 'alias', 'preview')->values()->dump();

        /** @var Product $item */
        $item = $this->repository->mustGetItem($id);

        $canPreview = $previewSecret && $this->pageService->secretVerify($item->id, (string) $previewSecret);

        if (!$canPreview && $item->state->isUnpublished()) {
            throw new RouteNotFoundException();
        }

        $variant = $this->orm->mustFindOne(ProductVariant::class, $item->primaryVariantId);
        $category = $this->orm->mustFindOne(Category::class, $item->categoryId);

        if (!$canPreview && $category->state->isUnpublished()) {
            throw new RouteNotFoundException();
        }

        // Keep URL unique
        if (($item->alias !== $alias) && !$app->service(Browser::class)->isRobot()) {
            return $this->nav->self()->alias($item->alias);
        }

        // Prepare variant view & price
        $variant = $this->variantService->prepareVariantView($variant, $item);

        // Sub Variants
        $variants = $this->orm->from(ProductVariant::class)
            ->where('product_id', $item->id)
            ->where('primary', '!=', 1)
            ->where('state', 1)
            ->all(ProductVariant::class);

        $minPrice = 0;
        $maxPrice = 0;

        /** @var ProductVariant $subVariant */
        foreach ($variants as $subVariant) {
            $minPrice = min($minPrice, $variant->price);
            $maxPrice = max($maxPrice, $variant->price);
        }

        // Features
        $features = $this->variantService->findFeaturesFromProduct($item);

        // Shippings
        $shippingIds = $item->shippings;

        $shippings = $this->orm->from(Shipping::class)
            ->where('state', 1)
            ->where('id', $shippingIds ?: [0])
            ->all(Shipping::class);

        // Discounts
        $discounts = $this->orm->from(Discount::class)
            ->where('type', DiscountType::PRODUCT)
            ->where('subtype', 'discount')
            ->where('product_id', $item->id)
            ->order('min_product_quantity', 'ASC')
            ->all(Discount::class);

        // Attributes
        /** @var Category[] $attrGroups */
        [$attributes, $attrGroups] = $this->productAttributeService->getAttributesAndGroupsWithValues(
            $item
        );

        $attributes = $attributes->filter(fn (ProductAttribute $attribute) => $attribute->display);
        $attributeSet = $attributes->groupBy('categoryId');

        foreach ($attrGroups as $group) {
            $params = $group->params;
            $params['attributes'] = $attributeSet[$group->id] ?? collect();

            $group->params = $params;
        }

        // Additional Purchases
        $additionalPurchases = $this->additionalPurchaseService->getAvailableVariants($item->id);

        // Tags
        $tags = $this->orm->select('tag.*')
            ->from(Tag::class)
            ->leftJoin(
                TagMap::class,
                'tag_map',
                [
                    ['tag_map.tag_id', 'tag.id'],
                    ['tag_map.type', val('product')]
                ]
            )
            ->where('tag_map.target_id', $item->id)
            ->order('tag.title')
            ->all(Tag::class);

        // Tabs
        $tabs = $this->getTabsByCategoryId($category->id);

        $this->prepareMetadata($app, $view, $item, $variant);

        // Wishlist
        $user = $this->userService->getUser();

        if ($user->isLogin()) {
            $favorited = $this->favoriteService->isFavorited('product', $user->id, $item->id);
        } else {
            $favorited = false;
        }

        return compact(
            'item',
            'variant',
            'category',
            'features',
            'discounts',
            'shippings',
            'attrGroups',
            'attributeSet',
            'tabs',
            'tags',
            'minPrice',
            'maxPrice',
            'additionalPurchases',
            'favorited'
        );
    }

    protected function prepareMetadata(AppContext $app, View $view, Product $item, ProductVariant $variant): void
    {
        $asset = $app->service(AssetService::class);
        $metadata = $item->meta;

        $view->setTitle($metadata->getOgTitle() ?: $metadata->getTitle() ?: $item->title);

        $htmlFrame = $view->getHtmlFrame();
        $htmlFrame->setDescription(
            (string) str($metadata->getDescription() ?: $item->description)->stripHtmlTags()
                ->truncate(200, '...')
        );

        $images[] = $asset->addAssetBase(
            $metadata->getOgImage() ?: $metadata->getCover() ?: $variant->cover
        );

        foreach ($variant->images as $image) {
            $images[] = $asset->addAssetBase($image['url']);
        }

        $htmlFrame->setCoverImages(...$images);

        if ($metadata->getKeywords()) {
            $htmlFrame->addMetadata('keywords', $metadata->getKeywords());
        }

        // Canonical
        $htmlFrame->addLink('canonical', (string) $item->makeLink($this->nav)->full());
    }

    protected function getTabsByCategoryId(int $categoryId): Collection
    {
        return $this->orm->from(ProductTab::class, 'tab')
            ->orWhere(
                function (Query $query) use ($categoryId) {
                    $query->whereNotExists(
                        fn(Query $query) => $query->from(ShopCategoryMap::class)
                            ->whereRaw('type = %q', 'tab')
                            ->whereRaw('target_id = tab.id')
                    );

                    $query->whereExists(
                        fn(Query $query) => $query->from(ShopCategoryMap::class)
                            ->whereRaw('type = %q', 'tab')
                            ->whereRaw('category_id = %a', $categoryId)
                            ->whereRaw('target_id = tab.id')
                    );
                }
            )
            ->order('tab.ordering', 'ASC')
            ->all(ProductTab::class);
    }

    /**
     * @param  ProductVariant  $variant
     *
     * @return  array{ 0: ProductVariant, 1: Product, 2: AdditionalPurchaseAttachment }
     *
     * @throws \ReflectionException
     */
    public function prepareAdditionalPurchase(ProductVariant $variant): array
    {
        $attachment = $this->orm->toEntity(AdditionalPurchaseAttachment::class, $variant->attachment);
        $product = $this->orm->toEntity(Product::class, $variant->product);

        $this->additionalPurchaseService->prepareVariantView($variant, $product, $attachment);

        return [$variant, $product, $attachment];
    }
}
