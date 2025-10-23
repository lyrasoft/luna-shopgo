<?php

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
