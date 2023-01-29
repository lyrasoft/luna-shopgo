<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App;

use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;

/**
 * The ShopGoPackage class.
 */
class ShopGoPackage extends AbstractPackage
{
    public function __construct(protected ApplicationInterface $app)
    {
        //
    }

    public function config(string $name, ?string $delimiter = '.'): mixed
    {
        return $this->app->config('shopgo' . $delimiter . $name, $delimiter);
    }

    public function install(PackageInstaller $installer): void
    {
    }

    public function useFullName(): bool
    {
        return (bool) $this->config('address.use_fullname');
    }

    public function useFullAddress(): bool
    {
        return (bool) $this->config('address.use_fulladdress');
    }
}
