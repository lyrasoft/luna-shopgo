<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Data;

use Windwalker\Data\ValueObject;

/**
 * The PaymentData class.
 */
class PaymentData extends ValueObject
{
    use PaymentShippingDataTrait;
}
