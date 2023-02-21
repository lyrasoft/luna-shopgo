<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Cart;

use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Entity\Address;
use Lyrasoft\ShopGo\Entity\Location;
use Psr\Cache\InvalidArgumentException;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\ORM\ORM;

use function Windwalker\collect;

/**
 * The CartView class.
 */
#[ViewModel(
    layout: 'cart',
    js: 'cart.js'
)]
class CartView implements ViewModelInterface
{
    /**
     * Constructor.
     */
    public function __construct(
        protected UserService $userService,
        protected Navigator $nav,
        protected ORM $orm,
        protected CartStorage $cartStorage,
    ) {
        //
    }

    /**
     * Prepare View.
     *
     * @param  AppContext  $app   The web app context.
     * @param  View        $view  The view object.
     *
     * @return  RouteUri|array
     * @throws InvalidArgumentException
     */
    public function prepare(AppContext $app, View $view): RouteUri|array
    {
        if ($this->cartStorage->count() === 0) {
            return $this->nav->to('home');
        }

        return [];
    }
}
