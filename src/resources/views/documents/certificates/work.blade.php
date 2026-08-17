<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Certificado de Trabajo - {{ $empleado['nombre'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .certificate {
            max-width: 210mm;
            margin: 0 auto;
            padding: 40px;
            border: 3px double #2c3e50;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .company-info {
            font-size: 10px;
            color: #666;
            margin-top: 10px;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 40px 0 30px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .content {
            text-align: justify;
            margin: 0 40px;
            font-size: 13px;
        }

        .content p {
            margin-bottom: 20px;
        }

        .employee-name {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin: 30px 0;
            padding: 15px;
            border-bottom: 2px solid #2c3e50;
            border-top: 2px solid #2c3e50;
        }

        .employee-dni {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .date {
            text-align: right;
            margin: 40px 0;
            font-style: italic;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding: 0 20px;
        }

        .signature {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin-bottom: 10px;
            margin-top: 50px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 11px;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        @media print {
            body { margin: 0; }
            .certificate { margin: 0; padding: 20px; border: none; }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="company-name">{{ $empresa['nombre'] }}</div>
            <div class="company-info">
                RUC: {{ $empresa['ruc'] }}<br>
                {{ $empresa['direccion'] }}
            </div>
        </div>

        <div class="title">Certificado de Trabajo</div>

        <div class="content">
            <p>La empresa <strong>{{ $empresa['nombre'] }}</strong>, representada por su Gerente General, certifica que:</p>

            <div class="employee-name">
                Sr(a). {{ strtoupper($empleado['nombre']) }}
            </div>
            <div class="employee-dni">
                DNI: {{ $empleado['dni'] }}
            </div>

            <p>ha laborado en nuestra empresa desde el <strong>{{ $empleado['fecha_inicio'] }}</strong> hasta la fecha, desempeñando el cargo de "<strong>{{ $empleado['cargo'] }}</strong>", con una remuneración mensual de <strong>{{ config('currency.symbol') }} {{ number_format($empleado['remuneracion'], 2) }}</strong> ({{ $empleado['remuneracion_letras'] ?? '' }}).</p>

            <p>Se extiende el presente certificado a solicitud del interesado(a) para los fines que estime conveniente.</p>
        </div>

        <div class="date">
            Lima, {{ $fecha_emision }}
        </div>

        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $firma_gerente['nombre'] ?? 'Gerente General' }}</div>
                <div class="signature-title">Gerente General</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $empleado['nombre'] }}</div>
                <div class="signature-title">{{ $empleado['cargo'] }}</div>
            </div>
        </div>

        <div class="footer">
            {{ $empresa['nombre'] }} - RUC: {{ $empresa['ruc'] }}<br>
            {{ $empresa['direccion'] }}
        </div>
    </div>
</body>
</html>
