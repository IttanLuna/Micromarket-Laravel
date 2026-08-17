<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario - {{ $periodo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .report {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .company-info {
            font-size: 10px;
            color: #666;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .date {
            font-size: 10px;
            color: #999;
            margin-bottom: 20px;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #3498db;
        }

        .summary h2 {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-table td:first-child {
            font-weight: bold;
            color: #555;
            width: 60%;
        }

        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
            color: #2c3e50;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tr:hover {
            background-color: #f0f0f0;
        }

        .status-ok {
            color: #27ae60;
            font-weight: bold;
        }

        .status-warning {
            color: #f39c12;
            font-weight: bold;
        }

        .status-danger {
            color: #e74c3c;
            font-weight: bold;
        }

        .recommendations {
            background-color: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            border-left: 4px solid #ffc107;
        }

        .recommendations h2 {
            font-size: 16px;
            color: #856404;
            margin-bottom: 15px;
        }

        .recommendations ul {
            margin-left: 20px;
        }

        .recommendations li {
            margin-bottom: 8px;
            color: #856404;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        @media print {
            body { margin: 0; }
            .report { margin: 0; padding: 10px; }
            .data-table tr:hover { background-color: transparent; }
        }
    </style>
</head>
<body>
    <div class="report">
        <div class="header">
            <div class="company-name">{{ config('app.name', 'MICROMARKET S.A.C.') }}</div>
            <div class="company-info">
                RUC: {{ config('app.ruc', '20512345678') }}<br>
                {{ config('app.direccion', 'Av. Principal 123, Lima') }}
            </div>
        </div>

        <div class="title">Reporte de Inventario</div>
        <div class="subtitle">Período: {{ $periodo }}</div>
        <div class="date">Generado: {{ $fecha_generacion }}</div>

        <div class="summary">
            <h2>Resumen Ejecutivo</h2>
            <table class="summary-table">
                <tr>
                    <td>Total productos:</td>
                    <td>{{ $resumen['total_productos'] }}</td>
                </tr>
                <tr>
                    <td>Stock bajo (&lt;10 unidades):</td>
                    <td>{{ $resumen['stock_bajo'] }} productos</td>
                </tr>
                <tr>
                    <td>Sin stock:</td>
                    <td>{{ $resumen['sin_stock'] }} productos</td>
                </tr>
                <tr>
                    <td>Valor total inventario:</td>
                    <td>{{ config('currency.symbol') }} {{ number_format($resumen['valor_total'], 2) }}</td>
                </tr>
            </table>
        </div>

        @if(!empty($productos))
        <div class="section">
            <h2>Productos con Stock Bajo</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Precio Unitario</th>
                        <th>Valor Stock</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto['nombre'] ?? '' }}</td>
                        <td>{{ $producto['categoria'] ?? '' }}</td>
                        <td>{{ $producto['stock'] ?? 0 }}</td>
                        <td>{{ $producto['stock_minimo'] ?? 10 }}</td>
                        <td>{{ config('currency.symbol') }} {{ number_format($producto['precio_venta'] ?? 0, 2) }}</td>
                        <td>{{ config('currency.symbol') }} {{ number_format(($producto['stock'] ?? 0) * ($producto['precio_venta'] ?? 0), 2) }}</td>
                        <td>
                            @if(($producto['stock'] ?? 0) == 0)
                                <span class="status-danger">SIN STOCK</span>
                            @elseif(($producto['stock'] ?? 0) < ($producto['stock_minimo'] ?? 10))
                                <span class="status-warning">BAJO</span>
                            @else
                                <span class="status-ok">OK</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="recommendations">
            <h2>Recomendaciones</h2>
            <ul>
                @if($resumen['sin_stock'] > 0)
                <li>Reabastecer urgentemente {{ $resumen['sin_stock'] }} producto(s) sin stock.</li>
                @endif
                @if($resumen['stock_bajo'] > 0)
                <li>Revisar {{ $resumen['stock_bajo'] }} producto(s) con stock por debajo del mínimo.</li>
                @endif
                <li>Realizar conteo físico para verificar existencias.</li>
                <li>Evaluar rotación de productos para optimizar compras.</li>
            </ul>
        </div>

        <div class="footer">
            {{ config('app.name', 'MICROMARKET S.A.C.') }} - RUC: {{ config('app.ruc', '20512345678') }}<br>
            Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
