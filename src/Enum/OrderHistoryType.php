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
 * The OrderHistoryType enum class.
 *
 * @method static $this MEMBER()
 * @method static $this ADMIN()
 * @method static $this SYSTEM()
 */
class OrderHistoryType extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const MEMBER = 'member';

    public const ADMIN = 'admin';

    public const SYSTEM = 'system';

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
        return $lang->trans('shopgo.order.history.type.' . $this->getKey());
    }
}
