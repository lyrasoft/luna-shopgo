<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use JetBrains\PhpStorm\Deprecated;
use Windwalker\Data\ValueObject;

/**
 * The AddressData to store shipping / payment info.
 */
#[\AllowDynamicProperties]
class AddressData extends ValueObject
{
    use AddressAwaitTrait;

    public int $addressId = 0;

    public int $locationId = 0;

    public string $address1 = '';

    public string $address2 = '';

    public string $fullAddress = '';
}
