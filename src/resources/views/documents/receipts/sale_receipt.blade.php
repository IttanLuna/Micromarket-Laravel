<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recibo de Venta - {{ $venta->numero }}</title>
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

        .payment-info {
            margin-top: 10px;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 4px;
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
        <div class="header">
            <div class="company-name">{{ $empresa['nombre'] }}</div>
            <div class="company-info">
                RUC: {{ $empresa['ruc'] }}<br>
                {{ $empresa['direccion'] }}<br>
                Tel: {{ $empresa['telefono'] }}
            </div>
        </div>

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
                @foreach($detalles as $index => $detalle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ config('currency.symbol') }} {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>{{ config('currency.symbol') }} {{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>{{ config('currency.symbol') }} {{ number_format($venta->subtotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span>IGV (18%):</span>
                <span>{{ config('currency.symbol') }} {{ number_format($venta->igv, 2) }}</span>
            </div>
            <div class="total-row total-final">
                <span>TOTAL:</span>
                <span>{{ config('currency.symbol') }} {{ number_format($venta->total, 2) }}</span>
            </div>
        </div>

        @if($venta->metodo_pago)
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">Método de pago:</span>
                <span>{{ ucfirst($venta->metodo_pago) }}</span>
            </div>
            @if($venta->monto_entregado)
            <div class="info-row">
                <span class="info-label">Monto entregado:</span>
                <span>{{ config('currency.symbol') }} {{ number_format($venta->monto_entregado, 2) }}</span>
            </div>
            @endif
            @if($venta->vuelto)
            <div class="info-row">
                <span class="info-label">Vuelto:</span>
                <span>{{ config('currency.symbol') }} {{ number_format($venta->vuelto, 2) }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="footer">
            ¡Gracias por su compra!<br>
            {{ $empresa['nombre'] }} - {{ $empresa['ruc'] }}<br>
            Documento impreso el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
