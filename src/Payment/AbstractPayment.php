<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Payment;

use Lyrasoft\ShopGo\Entity\Payment;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Renderer\CompositeRenderer;

/**
 * The AbstractPayment class.
 */
abstract class AbstractPayment implements FieldDefinitionInterface
{
    protected static string $type = '';

    protected Payment $data;

    abstract public static function getTypeIcon(): string;

    abstract public static function getTypeTitle(LangService $lang): string;

    abstract public static function getTypeDescription(LangService $lang): string;

    /**
     * @return string
     */
    public static function getType(): string
    {
        return static::$type;
    }

    /**
     * @param  string  $type
     *
     * @return  void
     */
    public static function setType(string $type): void
    {
        static::$type = $type;
    }

    public function getOptionLayout(): ?string
    {
        return null;
    }

    public function renderOptionLayout(CompositeRenderer $renderer, array $data = []): string
    {
        $layout = $this->getOptionLayout();

        if (!$layout) {
            return '';
        }

        $renderer->addPath(static::dir() . '/views');

        $data['payment'] = $this;

        return $renderer->render($layout, $data);
    }

    public function getCheckoutHandler(RouteUri|string $next): \Closure
    {
        return static function (Navigator $nav) use ($next) {
            return $next;
        };
    }

    /**
     * @return Payment
     */
    public function getData(): Payment
    {
        return $this->data;
    }

    /**
     * @param  Payment  $data
     *
     * @return  static  Return self to support chaining.
     */
    public function setData(Payment $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function &getParams(): array
    {
        $params = &$this->getData()->getParams();

        return $params;
    }

    public function __call(string $name, array $args): mixed
    {
        return $this->getData()->$name(...$args);
    }

    public static function dir(): string
    {
        $ref = new \ReflectionClass(static::class);

        return dirname($ref->getFileName());
    }
}
