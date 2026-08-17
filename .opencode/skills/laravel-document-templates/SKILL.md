---
name: laravel-document-templates
description: "Crear y mantener plantillas Blade profesionales para documentos PDF y Word en el MicroMarket. Diseño corporativo, layouts reutilizables y componentes."
---

# Laravel Document Templates Skill

Crea plantillas Blade profesionales para documentos del MicroMarket.

## Estructura de Plantillas

```
resources/views/documents/
├── layouts/
│   ├── receipt-layout.blade.php      # Layout para recibos
│   ├── report-layout.blade.php       # Layout para reportes
│   ├── certificate-layout.blade.php  # Layout para certificados
│   └── letter-layout.blade.php       # Layout para cartas
├── receipts/
│   └── sale_receipt.blade.php        # Recibo de venta
├── reports/
│   ├── inventory.blade.php           # Reporte inventario
│   └── sales.blade.php              # Reporte ventas
├── contracts/
│   └── supplier.blade.php           # Contrato proveedor
├── letters/
│   └── formal.blade.php             # Carta formal
├── certificates/
│   └── work.blade.php               # Certificado trabajo
└── components/
    ├── header.blade.php             # Cabecera
    ├── footer.blade.php             # Pie de página
    └── table.blade.php              # Tabla reutilizable
```

## Layout Maestro para Recibos

```php
{{-- resources/views/documents/layouts/receipt-layout.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Recibo' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .receipt {
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 9px;
            color: #666;
        }

        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .info-label {
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .items-table th {
            background-color: #f0f0f0;
            font-size: 9px;
            padding: 4px;
            text-align: left;
            border-bottom: 1px solid #000;
        }

        .items-table td {
            font-size: 10px;
            padding: 4px;
            border-bottom: 1px dashed #ccc;
        }

        .totals {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .total-final {
            font-size: 13px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 15px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }

        @media print {
            body { margin: 0; }
            .receipt { margin: 0; padding: 5px; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        @include('documents.components.header')
        
        {{ $slot }}
        
        @include('documents.components.footer')
    </div>
</body>
</html>
```

## Componente de Cabecera

```php
{{-- resources/views/documents/components/header.blade.php --}}
<div class="header">
    <div class="company-name">{{ $empresa['nombre'] }}</div>
    <div class="company-info">
        RUC: {{ $empresa['ruc'] }}<br>
        {{ $empresa['direccion'] }}<br>
        Tel: {{ $empresa['telefono'] }}
    </div>
</div>
```

## Recibo de Venta

```php
{{-- resources/views/documents/receipts/sale_receipt.blade.php --}}
<x-documents.layouts.receipt-layout title="Recibo de Venta">
    <div class="title">Recibo de Venta</div>
    
    <div class="info-row">
        <span class="info-label">N°:</span>
        <span>{{ $venta->numero }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Fecha:</span>
        <span>{{ $venta->created_at->format('d/m/Y H:i') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Cliente:</span>
        <span>{{ $venta->cliente->nombre }}</span>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cant.</th>
                <th>P.Unit.</th>
                <th>Subt.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $index => $detalle)
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

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>S/. {{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="total-row">
            <span>IGV (18%):</span>
            <span>S/. {{ number_format($venta->igv, 2) }}</span>
        </div>
        <div class="total-row total-final">
            <span>TOTAL:</span>
            <span>S/. {{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        ¡Gracias por su compra!<br>
        {{ $empresa['nombre'] }} - {{ $empresa['ruc'] }}
    </div>
</x-documents.layouts.receipt-layout>
```

## Reporte de Inventario

```php
{{-- resources/views/documents/reports/inventory.blade.php --}}
<x-documents.layouts.report-layout title="Reporte de Inventario">
    <h1>REPORTE DE INVENTARIO</h1>
    <p class="subtitle">Período: {{ $periodo }}</p>
    <p class="date">Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <h2>RESUMEN EJECUTIVO</h2>
        <table class="summary-table">
            <tr>
                <td>Total productos:</td>
                <td>{{ $resumen['total_productos'] }}</td>
            </tr>
            <tr>
                <td>Stock bajo (&lt;10):</td>
                <td>{{ $resumen['stock_bajo'] }} productos</td>
            </tr>
            <tr>
                <td>Sin stock:</td>
                <td>{{ $resumen['sin_stock'] }} productos</td>
            </tr>
            <tr>
                <td>Valor total inventario:</td>
                <td>S/. {{ number_format($resumen['valor_total'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="low-stock">
        <h2>PRODUCTOS CON STOCK BAJO</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Mínimo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos_bajo_stock as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>{{ $producto->stock_minimo }}</td>
                    <td>
                        @if($producto->stock == 0)
                            <span class="status-danger">CRÍTICO</span>
                        @else
                            <span class="status-warning">BAJO</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-documents.layouts.report-layout>
```

## Estilos para Reportes

```css
/* Agregar al layout de reportes */
.report {
    font-family: 'Arial', sans-serif;
    color: #333;
}

.report h1 {
    font-size: 24px;
    color: #2c3e50;
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.report h2 {
    font-size: 18px;
    color: #34495e;
    margin: 20px 0 10px 0;
}

.summary-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.summary-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.data-table th {
    background-color: #3498db;
    color: white;
    padding: 10px;
    text-align: left;
}

.data-table td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.data-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.status-warning {
    color: #f39c12;
    font-weight: bold;
}

.status-danger {
    color: #e74c3c;
    font-weight: bold;
}
```

## Certificado de Trabajo

```php
{{-- resources/views/documents/certificates/work.blade.php --}}
<x-documents.layouts.certificate-layout title="Certificado de Trabajo">
    <div class="certificate-content">
        <p>La empresa <strong>{{ $empresa['nombre'] }}</strong>, representada por su Gerente General, certifica que:</p>

        <div class="employee-name">
            Sr(a). {{ strtoupper($empleado['nombre']) }}
        </div>
        <div class="employee-dni">
            DNI: {{ $empleado['dni'] }}
        </div>

        <p>ha laborado en nuestra empresa desde el {{ $empleado['fecha_inicio'] }} hasta la fecha, desempeñando el cargo de "{{ $empleado['cargo'] }}", con una remuneración mensual de S/. {{ number_format($empleado['remuneracion'], 2) }} ({{ $empleado['remuneracion_letras'] }}).</p>

        <p>Se extiende el presente certificado a solicitud del interesado(a) para los fines que estime conveniente.</p>

        <div class="date">
            Lima, {{ $fecha_emision }}
        </div>

        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $firma_gerente['nombre'] }}</div>
                <div class="signature-title">Gerente General</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $empleado['nombre'] }}</div>
                <div class="signature-title">{{ $empleado['cargo'] }}</div>
            </div>
        </div>
    </div>
</x-documents.layouts.certificate-layout>
```

## Errores Comunes

| Error | Solución |
|-------|----------|
| View not found | Verificar ruta de la plantilla |
| Variable undefined | Pasar todas las variables necesarias |
| CSS no aplica | Verificar que el CSS esté inline o en el layout |
| Imágenes no aparecen | Usar rutas absolutas o base64 |
