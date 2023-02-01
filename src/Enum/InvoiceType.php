<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

/**
 * The InvoiceType enum class.
 *
 * @method static $this IDV()
 * @method static $this COMPANY()
 */
class InvoiceType extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const IDV = 'idv';

    public const COMPANY = 'company';

    /**
     * Unable to directly new this object.
     *
     * @param  mixed  $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    protected function __construct(mixed $value)
    {
        parent::__construct($value);
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.invoice.type.' . $this->getKey());
    }
}
