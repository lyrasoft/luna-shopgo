<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Checkout;

use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Entity\Order;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\TypeCast;

use function Windwalker\count;

/**
 * The CheckoutView class.
 */
#[ViewModel(
    layout: [
        'checkout',
        'shipping',
        'payment',
        'complete',
    ],
    js: 'checkout.js'
)]
class CheckoutView implements ViewModelInterface
{
    use TranslatorTrait;

    /**
     * Constructor.
     */
    public function __construct(protected ORM $orm, protected Navigator $nav, protected CartStorage $cartStorage)
    {
        //
    }

    /**
     * Prepare View.
     *
     * @param  AppContext  $app   The web app context.
     * @param  View        $view  The view object.
     *
     * @return  mixed
     */
    public function prepare(AppContext $app, View $view): mixed
    {
        if ($view->getLayout() === 'complete') {
            $cleared = $app->input('cleared');

            if (!$cleared) {
                if (!WINDWALKER_DEBUG) {
                    $this->cartStorage->clearChecked();
                    $app->state->forget('checkout.data');
                }

                return $this->nav->self()->var('cleared', '1');
            }

            $no = (string) $app->input('no');

            $order = $this->orm->findOne(Order::class, compact('no'));

            if (!$order) {
                return $this->nav->to('home');
            }

            return compact('order');
        }

        return [];
    }

    public function flattenAsInputName(
        object|array $array,
        ?string $prefix = null
    ): array {
        $temp = [];

        foreach (TypeCast::toArray($array, false) as $k => $v) {
            $key = $prefix !== null ? $prefix . "[$k]" : $k;

            if (is_array($v)) {
                $temp[] = $this->flattenAsInputName($v, (string) $key);
            } else {
                $temp[] = [$key => $v];
            }
        }

        // Prevent resource-greedy loop.
        // @see https://github.com/dseguy/clearPHP/blob/master/rules/no-array_merge-in-loop.md
        if (count($temp)) {
            return array_merge(...$temp);
        }

        return [];
    }

    protected function prepareMetadata(AppContext $app, View $view): void
    {
        $view->setTitle(
            $this->trans('shopgo.checkout.page.title')
        );
    }
}
