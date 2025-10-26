<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Component;

use Closure;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Currency\CurrencyResolver;
use Lyrasoft\ShopGo\Service\VariantService;
use Windwalker\Core\Edge\Attribute\EdgeComponent;
use Windwalker\Edge\Component\AbstractComponent;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Attributes\Prop;

#[EdgeComponent('product-card')]
class ProductCardComponent extends AbstractComponent
{
    #[Prop]
    public object $item;

    #[Prop]
    public ?object $variant = null;

    #[Prop]
    public ?bool $favorited = null;

    public function __construct(
        protected ORM $orm,
        protected CurrencyResolver $currencyResolver,
        protected VariantService $variantService
    ) {
    }

    public function render(): Closure|string
    {
        return 'components.product-card';
    }

    public function data(): array
    {
        $item = $this->orm->toEntity(Product::class, $this->item);
        $variant = $this->orm->toEntity(ProductVariant::class, $this->variant ?? $this->item->variant);

        $variant = $this->variantService->prepareVariantView($variant, $item);
        $isOutOfStock = $this->variantService::isOutOfStock($variant, $item);

        return [
            ...parent::data(),
            ...compact('item', 'variant', 'isOutOfStock'),
        ];
    }

    public function priceFormat(mixed $num): string
    {
        return $this->currencyResolver->format($num);
    }
}
