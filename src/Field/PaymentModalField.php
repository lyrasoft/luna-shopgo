<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\Payment;
use Unicorn\Field\ModalField;

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
