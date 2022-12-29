<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Manufacturer;

use App\Entity\Manufacturer;
use App\Module\Admin\Manufacturer\Form\EditForm;
use App\Repository\ManufacturerRepository;
use Lyrasoft\Luna\Entity\TagMap;
use Lyrasoft\Luna\Locale\LanguageAssocTrait;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

/**
 * The ManufacturerEditView class.
 */
#[ViewModel(
    layout: 'manufacturer-edit',
    js: 'manufacturer-edit.js'
)]
class ManufacturerEditView implements ViewModelInterface
{
    use LanguageAssocTrait;
    use LocaleAwareTrait;
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected ManufacturerRepository $repository
    ) {
    }

    /**
     * Prepare
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  mixed
     */
    public function prepare(AppContext $app, View $view): mixed
    {
        $id = $app->input('id');

        /** @var Manufacturer $item */
        $item = $this->repository->getItem($id);

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            )->fill(
                [
                    'meta_title' => $item?->getMeta()['title'] ?? '',
                    'meta_description' => $item?->getMeta()['description'] ?? '',
                    'meta_keywords' => $item?->getMeta()['keywords'] ?? '',
                ]
            );

        if ($item) {
            // Tags
            $tagIds = $this->orm->findColumn(
                TagMap::class,
                'tag_id',
                ['type' => 'manufacturer', 'target_id' => $item->id]
            )->dump();

            $form->fill(['tags' => $tagIds]);
        }

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item');
    }

    /**
     * Prepare Metadata and HTML Frame.
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  void
     */
    protected function prepareMetadata(AppContext $app, View $view): void
    {
        $view->getHtmlFrame()
            ->setTitle(
                $this->trans('unicorn.title.edit', title: $this->trans('shopgo.manufacturer.title'))
            );
    }
}
