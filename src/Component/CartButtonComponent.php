<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Component;

use Closure;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Windwalker\Core\Edge\Attribute\EdgeComponent;
use Windwalker\Core\Router\Navigator;
use Windwalker\Edge\Component\AbstractComponent;
use Windwalker\Edge\Component\ComponentAttributes;
use Windwalker\Utilities\Attributes\Prop;

#[EdgeComponent('cart-button')]
class CartButtonComponent extends AbstractComponent
{
    #[Prop]
    public mixed $link = true;

    #[Prop]
    public ?string $target = null;

    public function __construct(protected CartStorage $cartStorage, protected Navigator $nav)
    {
    }

    public function render(): Closure|string
    {
        return 'components.cart-button';
    }

    public function data(): array
    {
        $cartQuantity = $this->cartStorage->count();

        return [
            ...compact('cartQuantity'),
            ...parent::data()
        ];
    }
}
