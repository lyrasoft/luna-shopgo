<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Traits;

use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\DI\Attributes\Inject;

/**
 * Trait AppAwareTrait
 */
trait AppAwareTrait
{
    #[Inject]
    protected ApplicationInterface $app;
}
