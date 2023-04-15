<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Invoice;

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Service\InvoiceService;
use Lyrasoft\ShopGo\ShopGoPackage;
use Psr\Http\Message\ResponseInterface;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Core\View\View;
use Windwalker\Http\Response\AttachmentResponse;
use Windwalker\ORM\ORM;
use Windwalker\Stream\Stream;

use const Windwalker\Stream\READ_WRITE_FROM_BEGIN;

/**
 * The InvoiceController class.
 */
#[Controller(
    config: __DIR__ . '/invoice.config.php'
)]
class InvoiceController
{
    use TranslatorTrait;

    public function create(AppContext $app, ORM $orm, Navigator $nav, InvoiceService $invoiceService): RouteUri
    {
        $id = (int) $app->input('id');

        $order = $orm->mustFindOne(Order::class, $id);

        if (!$order->getInvoiceNo()) {
            $order->setInvoiceNo($invoiceService->genInvoiceNumber());
        }

        $orm->updateOne(Order::class, $order);

        $app->addMessage(
            $this->trans(
                'shopgo.order.message.create.invoice.success'
            ),
            'success'
        );

        return $nav->back();
    }

    public function index(AppContext $app, string $view): ResponseInterface
    {
        /** @var View $viewInstance */
        $viewInstance = $app->make($view);

        $res = $viewInstance->render();

        $pdf = $app->input('pdf');
        $id = $app->input('id');

        if ($pdf) {
            $html = (string) $res->getBody();

            return $this->outputPdf($app, $id, $html);
        }

        return $res;
    }

    protected function outputPdf(AppContext $app, int $id, string $html): AttachmentResponse
    {
        $shopGo = $app->service(ShopGoPackage::class);
        $invoiceService = $app->service(InvoiceService::class);
        $orm = $app->service(ORM::class);

        $order = $orm->mustFindOne(Order::class, $id);

        $pdfData = $invoiceService->renderPdf($html);
        $stream = new Stream('php://memory', READ_WRITE_FROM_BEGIN);
        $stream->write($pdfData);
        $stream->rewind();

        return (new AttachmentResponse($stream, 200))
            ->withHeader('Content-Type', 'application/pdf')
            ->withInlineFilename(
                sprintf(
                    '[%s] Invoice-%s.pdf',
                    $shopGo->config('shop.sitename') ?: 'ShopGo',
                    $order->getInvoiceNo()
                )
            );
    }
}
