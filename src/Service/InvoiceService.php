<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Sequence\Service\SequenceService;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Module\Admin\Invoice\InvoiceView;
use Lyrasoft\ShopGo\ShopGoPackage;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\View\View;
use Windwalker\ORM\ORM;

/**
 * The InvoiceService class.
 */
class InvoiceService
{
    public function __construct(
        protected ApplicationInterface $app,
        protected SequenceService $sequenceService,
        protected ShopGoPackage $shopGo
    ) {
    }

    public function getPrefix(): string
    {
        return (string) ($this->shopGo->config('invoice_no.prefix') ?: 'INV-');
    }

    public function getLength(): int
    {
        return (int) ($this->shopGo->config('invoice_no.length') ?: 11);
    }

    public function genInvoiceNumber(): string
    {
        return $this->getPrefix()
            . $this->sequenceService->getNextSerialAndPadZero(
                'shopgo_invoice',
                $this->getPrefix(),
                $this->getLength()
            );
    }

    public function createAndRenderInvoice(Order $order): string
    {
        if (!$order->getInvoiceNo()) {
            $this->genAndSaveInvoiceNoForOrder($order);
        }

        /** @var View $view */
        $view = $this->app->make(InvoiceView::class);
        $res = $view->render(['id' => $order->getId()]);
        return (string) $res->getBody();
    }

    public function createAndRenderInvoicePdf(Order $order): string
    {
        return $this->renderPdf($this->createAndRenderInvoice($order));
    }

    public function renderPdf(string $html): string
    {
        if (!class_exists(Mpdf::class)) {
            throw new \DomainException(
                'Please install `mpdf/mpdf psr/log:^2.0` first.'
            );
        }

        $shopGo = $this->shopGo;

        // Get default config
        $defaultConfig = (new ConfigVariables())
            ->getDefaults();

        $fontDirs = $defaultConfig['fontDir'];
        $fontDirs = array_merge(
            $fontDirs,
            [
                $shopGo::dir() . '/resources/fonts',
            ]
        );

        $fontDirs = array_merge(
            $fontDirs,
            (array) $shopGo->config('mpdf.font_dirs')
        );

        // 預設字體設定
        $defaultFontConfig = (new FontVariables())
            ->getDefaults();

        $fontData = $defaultFontConfig['fontdata'];

        $fontData = ((array) $shopGo->config('mpdf.font_data')) + $fontData;

        $mpdf = new Mpdf(
            [
                'fontDir' => $fontDirs,
                'fontdata' => $fontData,
            ]
        );

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * @param  Order  $order
     *
     * @return  void
     */
    protected function genAndSaveInvoiceNoForOrder(Order $order): void
    {
        $orm = $this->app->service(ORM::class);

        $no = $this->genInvoiceNumber();

        $order->setInvoiceNo($no);

        $orm->updateBatch(
            Order::class,
            ['invoice_no' => $no],
            ['id' => $order->getId()]
        );
    }
}
