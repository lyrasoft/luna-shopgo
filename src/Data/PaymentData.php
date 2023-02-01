<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Data;

use Lyraoft\ShopGo\Data\Contract\AddressAwareInterface;
use Windwalker\Data\ValueObject;

/**
 * The PaymentData class.
 */
class PaymentData extends ValueObject implements AddressAwareInterface
{
    use AddressAwaitTrait;
}
