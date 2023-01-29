<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Enum;

use MyCLabs\Enum\Enum;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;
use Windwalker\Utilities\Contract\LanguageInterface;

/**
 * The OrderNoMode enum class.
 *
 * @method static $this INCREMENT_ID()
 * @method static $this DAILY_SEQUENCE()
 * @method static $this SEQUENCE_HASHES()
 * @method static $this RANDOM_HASHES()
 */
class OrderNoMode extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const INCREMENT_ID = 'increment_id';
    public const DAILY_SEQUENCE = 'daily_sequence';
    public const SEQUENCE_HASHES = 'sequence_hashes';
    public const RANDOM_HASHES = 'random_hashes';

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
        return $lang->trans('shopgo.order.no.mode.' . $this->getKey());
    }
}
