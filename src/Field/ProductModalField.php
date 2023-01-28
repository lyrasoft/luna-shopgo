<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Field;

use App\Entity\Product;
use Unicorn\Field\ModalField;
use Unicorn\Image\ImagePlaceholder;
use Windwalker\DI\Attributes\Inject;

/**
 * The ProductModalField class.
 */
class ProductModalField extends ModalField
{
    #[Inject]
    protected ImagePlaceholder $imagePlaceholder;

    protected function configure(): void
    {
        $this->route('product_list');
        $this->table(Product::class);
    }

    protected function getItemTitle(): ?string
    {
        return $this->getItem()['title'] ?? '';
    }

    protected function getItemImage(): ?string
    {
        return $this->getItem()['cover'] ?? $this->imagePlaceholder->placeholderSquare();
    }
}
