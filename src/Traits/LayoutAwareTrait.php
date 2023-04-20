<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Traits;

use Windwalker\Core\Renderer\RendererService;
use Windwalker\Renderer\CompositeRenderer;

/**
 * Trait LayoutAwareTrait
 */
trait LayoutAwareTrait
{
    use AppAwareTrait;

    protected function getBasePath(): string
    {
        $ref = new \ReflectionClass(static::class);

        return dirname((string) $ref->getFileName()) . '/views';
    }

    protected function registerRendererPaths(CompositeRenderer $renderer): CompositeRenderer
    {
        $renderer->addPath(WINDWALKER_VIEWS . '/shipping/basic');
        $renderer->addPath($this->getBasePath());

        return $renderer;
    }

    public function getRenderer(): CompositeRenderer
    {
        $rendererService = $this->app->service(RendererService::class);
        /** @var CompositeRenderer $renderer */
        $renderer = $rendererService->createRenderer();

        return $this->registerRendererPaths($renderer);
    }

    public function renderLayout(string $layout, array $data = [], array $options = []): string
    {
        return trim(
            $this->getRenderer()
                ->render(
                    $layout,
                    $data,
                    $options
                )
        );
    }
}
