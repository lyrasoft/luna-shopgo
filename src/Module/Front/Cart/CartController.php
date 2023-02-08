<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Cart;

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;

/**
 * The CartController class.
 */
#[Controller]
class CartController
{
    public function ajax(AppContext $app): mixed
    {
        $task = $app->input('task');

        return $app->call([$this, $task]);
    }

    public function addToCart()
    {

    }
}
