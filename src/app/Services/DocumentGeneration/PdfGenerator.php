<?php

namespace App\Services\DocumentGeneration;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfGenerator
{
    /**
     * Generar recibo de venta en PDF
     */
    public function generarRecibo(array $datos): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('documents.receipts.sale_receipt', $datos)
            ->setPaper('letter', 'portrait')
            ->setOption('is-html5-parser-enabled', true)
            ->setOption('is-font-subsetting-enabled', true);
    }

    /**
     * Generar reporte de inventario en PDF
     */
    public function generarReporteInventario(array $datos): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('documents.reports.inventory', $datos)
            ->setPaper('letter', 'portrait')
            ->setOption('is-html5-parser-enabled', true);
    }

    /**
     * Generar contrato de proveedor en PDF
     */
    public function generarContrato(array $datos): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('documents.contracts.supplier', $datos)
            ->setPaper('letter', 'portrait')
            ->setOption('is-html5-parser-enabled', true);
    }

    /**
     * Generar carta formal en PDF
     */
    public function generarCarta(array $datos): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('documents.letters.formal', $datos)
            ->setPaper('letter', 'portrait')
            ->setOption('is-html5-parser-enabled', true);
    }

    /**
     * Generar certificado de trabajo en PDF
     */
    public function generarCertificado(array $datos): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('documents.certificates.work', $datos)
            ->setPaper('letter', 'portrait')
            ->setOption('is-html5-parser-enabled', true);
    }
}
