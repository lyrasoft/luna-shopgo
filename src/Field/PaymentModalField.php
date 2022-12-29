<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Field;

use App\Entity\Payment;
use Unicorn\Field\ModalField;
use Windwalker\DOM\DOMElement;
use Windwalker\Form\Field\AbstractField;

/**
 * The PaymentModalField class.
 */
class PaymentModalField extends ModalField
{
    protected ?string $table = Payment::class;

    protected function configure(): void
    {
        $this->route('payment_list');
        $this->table(Payment::class);
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors(): array
    {
        return array_merge(
            parent::getAccessors(),
            []
        );
    }
}
