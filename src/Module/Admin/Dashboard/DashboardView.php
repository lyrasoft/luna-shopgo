<?php

/**
 * Part of starter project.
 *
 * @copyright      Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Dashboard;

use Brick\Math\BigNumber;
use Lyrasoft\Toolkit\Encode\BaseConvert;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Crypt\PseudoCrypt;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\ORM\ORM;

use function Windwalker\tid;
use function Windwalker\uid;

/**
 * The DashboardView class.
 */
#[ViewModel(
    layout: 'dashboard',
    js: 'dashboard.js'
)]
class DashboardView implements ViewModelInterface
{
    use TranslatorTrait;

    /**
     * DashboardView constructor.
     */
    public function __construct(protected ORM $orm)
    {
        //
    }

    /**
     * Prepare View.
     *
     * @param  AppContext  $app   The web app context.
     * @param  View        $view  The view object.
     *
     * @return    mixed
     */
    public function prepare(AppContext $app, View $view): array
    {
        $view->setTitle($this->trans('unicorn.title.dashboard'));

        $t = (string) time();
        $hex = BaseConvert::encode($t, BaseConvert::BASE62);

        $pc = new PseudoCrypt();
        show(
            $t,
            $hex,
            3
        );

        return [];
    }
}
