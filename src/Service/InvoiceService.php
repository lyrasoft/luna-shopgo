<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Sequence\Service\SequenceService;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\ShopGoPackage;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * The InvoiceService class.
 */
class InvoiceService
{
    public function __construct(protected SequenceService $sequenceService, protected ShopGoPackage $shopGo)
    {
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
        return $this->getPrefix() . $this->sequenceService->getNextSerialAndPadZero(
            'shopgo_invoice',
            $this->getPrefix(),
            $this->getLength()
        );
    }

    public function renderPdf(string $html): string
    {
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
                'fontdata' => $fontData
            ]
        );

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}
