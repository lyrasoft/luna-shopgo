<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping\Basic;

use Lyrasoft\ShopGo\Shipping\AbstractShipping;
use Windwalker\Core\Language\LangService;
use Windwalker\Form\Form;

/**
 * The BasicShipping class.
 */
class BasicShipping extends AbstractShipping
{
    public static function getTypeIcon(): string
    {
        return 'fa fa-truck';
    }

    public static function getTypeTitle(LangService $lang): string
    {
        return $lang('shopgo.shipping.basic.title');
    }

    public static function getTypeDescription(LangService $lang): string
    {
        return $lang('shopgo.shipping.basic.description');
    }

    public function define(Form $form): void
    {
        //
    }
}
