---
name: laravel-document-pdf
description: "Generación de documentos PDF en Laravel usando barryvdh/laravel-dompdf. Crear recibos, reportes, contratos, cartas y certificados con diseño profesional."
---

# Laravel Document PDF Skill

Genera documentos PDF profesionales usando DomPDF en Laravel.

## Configuración

```php
// config/dompdf.php (opcional, personalizar)
return [
    'default_font' => 'sans-serif',
    'default_paper_size' => 'letter',
    'dpi' => 150,
];
```

## Uso Básico

### Generar PDF desde Vista Blade

```php
use Barryvdh\DomPDF\Facade\Pdf;

// Desde una vista Blade
$pdf = Pdf::loadView('documents.receipts.sale_receipt', [
    'venta' => $venta,
    'detalles' => $detalles,
    'empresa' => $empresa,
]);

return $pdf->download('recibo-venta.pdf');
```

### Generar PDF con Datos Manuales

```php
$pdf = Pdf::loadView('documents.reports.inventory', [
    'productos' => $productos,
    'resumen' => $resumen,
    'periodo' => 'Agosto 2026',
]);

return $pdf->stream('reporte-inventario.pdf');
```

## Plantilla Blade para PDF

```php
{{-- resources/views/documents/receipts/sale_receipt.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Venta</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $empresa['nombre'] }}</div>
        <div>RUC: {{ $empresa['ruc'] }}</div>
        <div>{{ $empresa['direccion'] }}</div>
    </div>

    <h2>RECIBO DE VENTA N°: {{ $venta->numero }}</h2>
    <p>Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</p>
    <p>Cliente: {{ $venta->cliente->nombre }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $index => $detalle)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>S/. {{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>S/. {{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Subtotal: S/. {{ number_format($venta->subtotal, 2) }}</p>
        <p>IGV (18%): S/. {{ number_format($venta->igv, 2) }}</p>
        <p>TOTAL: S/. {{ number_format($venta->total, 2) }}</p>
    </div>
</body>
</html>
```

## Opciones de PDF

```php
// Personalizar tamaño de papel
$pdf = Pdf::loadView('view', $data)->setPaper('a4', 'landscape');

// Orientación
$pdf = Pdf::loadView('view', $data)->setPaper('letter', 'portrait');

// Personalizar formato
$pdf = Pdf::loadView('view', $data)
    ->setOption('font-size', 10)
    ->setOption('is-html5-parser-enabled', true);
```

## Errores Comunes

| Error | Solución |
|-------|----------|
| Font not found | Usar fuentes del sistema o incluir en public/fonts |
| Memory limit | Aumentar `memory_limit` en php.ini |
| Timeout | Aumentar `max_execution_time` |
| HTML rendering | Verificar que el HTML sea válido |

## Ejemplo Completo

```php
<?php

namespace App\Services\DocumentGeneration;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Venta;

class PdfGenerator
{
    public function generarRecibo(Venta $venta): \Barryvdh\DomPDF\PDF
    {
        $venta->load('detalles.producto', 'cliente');
        
        $empresa = [
            'nombre' => 'MICROMARKET S.A.C.',
            'ruc' => '20512345678',
            'direccion' => 'Av. Principal 123, Lima',
            'telefono' => '(01) 555-1234',
        ];

        return Pdf::loadView('documents.receipts.sale_receipt', [
            'venta' => $venta,
            'detalles' => $venta->detalles,
            'empresa' => $empresa,
        ]);
    }
}
```
