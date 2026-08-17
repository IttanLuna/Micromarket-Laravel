<?php

namespace App\Services\DocumentGeneration;

use PhpOffice\PhpWord\Document;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;

class WordGenerator
{
    /**
     * Generar recibo de venta en Word
     */
    public function generarRecibo(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Cabecera de la empresa
        $this->agregarCabecera($section, $datos['empresa']);

        // Título
        $section->addTitle('RECIBO DE VENTA', 1);

        // Información de la venta
        $venta = $datos['venta'];
        $section->addParagraph("Número: {$venta->numero}");
        $section->addParagraph("Fecha: {$venta->created_at->format('d/m/Y H:i')}");
        $section->addParagraph("Cliente: {$venta->cliente->nombre}");

        // Tabla de productos
        $table = $section->addTable([
            'borderSize' => 1,
            'borderColor' => '000000',
        ]);

        // Encabezados
        $table->addRow();
        $this->agregarCeldaEncabezado($table, '#');
        $this->agregarCeldaEncabezado($table, 'Producto');
        $this->agregarCeldaEncabezado($table, 'Cant.');
        $this->agregarCeldaEncabezado($table, 'P.Unit.');
        $this->agregarCeldaEncabezado($table, 'Subtotal');

        // Detalles
        foreach ($datos['detalles'] as $index => $detalle) {
            $table->addRow();
            $table->addCell($index + 1);
            $table->addCell($detalle->producto->nombre);
            $table->addCell($detalle->cantidad);
            $table->addCell(config('currency.symbol') . ' ' . number_format($detalle->precio_unitario, 2));
            $table->addCell(config('currency.symbol') . ' ' . number_format($detalle->subtotal, 2));
        }

        // Totales
        $section->addParagraph('');
        $section->addParagraph("Subtotal: " . config('currency.symbol') . " " . number_format($venta->subtotal, 2));
        $section->addParagraph("IGV (18%): " . config('currency.symbol') . " " . number_format($venta->igv, 2));
        $section->addParagraph("TOTAL: " . config('currency.symbol') . " " . number_format($venta->total, 2), ['bold' => true]);

        return $phpWord;
    }

    /**
     * Generar reporte de inventario en Word
     */
    public function generarReporteInventario(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Título
        $section->addTitle('REPORTE DE INVENTARIO', 1);
        $section->addParagraph("Período: {$datos['periodo']}");
        $section->addParagraph("Generado: {$datos['fecha_generacion']}");

        // Resumen
        $section->addTitle('RESUMEN EJECUTIVO', 2);
        $section->addParagraph("Total productos: {$datos['resumen']['total_productos']}");
        $section->addParagraph("Stock bajo: {$datos['resumen']['stock_bajo']} productos");
        $section->addParagraph("Sin stock: {$datos['resumen']['sin_stock']} productos");
        $section->addParagraph("Valor total: " . config('currency.symbol') . " " . number_format($datos['resumen']['valor_total'], 2));

        // Tabla de productos
        if (!empty($datos['productos'])) {
            $section->addTitle('LISTADO DE PRODUCTOS', 2);

            $table = $section->addTable([
                'borderSize' => 1,
                'borderColor' => '000000',
            ]);

            $table->addRow();
            $this->agregarCeldaEncabezado($table, 'Producto');
            $this->agregarCeldaEncabezado($table, 'Categoría');
            $this->agregarCeldaEncabezado($table, 'Stock');
            $this->agregarCeldaEncabezado($table, 'Precio');

            foreach ($datos['productos'] as $producto) {
                $table->addRow();
                $table->addCell($producto['nombre'] ?? '');
                $table->addCell($producto['categoria'] ?? '');
                $table->addCell($producto['stock'] ?? 0);
                $table->addCell(config('currency.symbol') . ' ' . number_format($producto['precio_venta'] ?? 0, 2));
            }
        }

        return $phpWord;
    }

    /**
     * Generar contrato de proveedor en Word
     */
    public function generarContrato(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Título
        $section->addTitle('CONTRATO DE PROVEEDOR', 1);

        // Fecha
        $section->addParagraph("Fecha: {$datos['fecha']}");
        $section->addParagraph('');

        // Partes
        $section->addTitle('PARTES', 2);
        $section->addParagraph("EL PROVEEDOR: {$datos['proveedor']['nombre']}");
        $section->addParagraph("RUC: {$datos['proveedor']['ruc']}");
        $section->addParagraph("Dirección: {$datos['proveedor']['direccion']}");
        $section->addParagraph('');

        // Cláusulas
        $section->addTitle('CLÁUSULAS', 2);
        foreach ($datos['clausulas'] as $index => $clausula) {
            $section->addTitle("Cláusula " . ($index + 1) . ": {$clausula['titulo']}", 3);
            $section->addParagraph($clausula['texto']);
        }

        // Firmas
        $section->addParagraph('');
        $section->addParagraph('');

        $table = $section->addTable();
        $table->addRow();
        $table->addCell('______________________');
        $table->addCell('______________________');
        $table->addRow();
        $table->addCell('EL PROVEEDOR');
        $table->addCell('MICROMARKET S.A.C.');

        return $phpWord;
    }

    /**
     * Generar carta formal en Word
     */
    public function generarCarta(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Cabecera
        $this->agregarCabecera($section, $datos['empresa'] ?? [
            'nombre' => 'MICROMARKET S.A.C.',
            'ruc' => '20512345678',
            'direccion' => 'Av. Principal 123, Lima',
        ]);

        // Fecha
        $section->addParagraph("Lima, {$datos['fecha']}");
        $section->addParagraph('');

        // Destinatario
        $section->addParagraph("Señor(a): {$datos['destinatario']['nombre']}");
        $section->addParagraph("Cargo: {$datos['destinatario']['cargo']}");
        $section->addParagraph("Presente.-");
        $section->addParagraph('');

        // Asunto
        $section->addTitle("Asunto: {$datos['asunto']}", 2);
        $section->addParagraph('');

        // Cuerpo
        $section->addParagraph($datos['cuerpo']);

        // Despedida
        $section->addParagraph('');
        $section->addParagraph('Atentamente,');
        $section->addParagraph('');
        $section->addParagraph("{$datos['firmante']['nombre']}");
        $section->addParagraph("{$datos['firmante']['cargo']}");

        return $phpWord;
    }

    /**
     * Generar certificado de trabajo en Word
     */
    public function generarCertificado(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Cabecera
        $this->agregarCabecera($section, $datos['empresa'] ?? [
            'nombre' => 'MICROMARKET S.A.C.',
            'ruc' => '20512345678',
            'direccion' => 'Av. Principal 123, Lima',
        ]);

        // Título
        $section->addTitle('CERTIFICADO DE TRABAJO', 1);

        // Cuerpo
        $section->addParagraph("La empresa {$datos['empresa']['nombre']} certifica que:");
        $section->addParagraph('');

        $section->addParagraph("Sr(a). " . strtoupper($datos['empleado']['nombre']), [
            'bold' => true,
            'size' => 14,
        ]);
        $section->addParagraph("DNI: {$datos['empleado']['dni']}");
        $section->addParagraph('');

        $texto = "ha laborado en nuestra empresa desde el {$datos['empleado']['fecha_inicio']} ";
        $texto .= "hasta la fecha, desempeñando el cargo de \"{$datos['empleado']['cargo']}\"";
        $texto .= ", con una remuneración mensual de " . config('currency.symbol') . " " . number_format($datos['empleado']['remuneracion'], 2);
        $texto .= ".";
        $section->addParagraph($texto);

        $section->addParagraph('');
        $section->addParagraph('Se extiende el presente certificado a solicitud del interesado(a).');
        $section->addParagraph('');

        // Fecha
        $section->addParagraph("Lima, {$datos['fecha_emision']}");
        $section->addParagraph('');

        // Firmas
        $table = $section->addTable();
        $table->addRow();
        $table->addCell('______________________');
        $table->addCell('______________________');
        $table->addRow();
        $table->addCell('Gerente General');
        $table->addCell($datos['empleado']['nombre']);

        return $phpWord;
    }

    /**
     * Guardar documento Word
     */
    public function guardar(Document $phpWord, string $filename): string
    {
        $path = storage_path("app/{$filename}");
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $path;
    }

    /**
     * Descargar documento Word
     */
    public function descargar(Document $phpWord, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->stream(function () use ($phpWord) {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => "attachment;filename=\"{$filename}\"",
        ]);
    }

    /**
     * Agregar cabecera a la sección
     */
    private function agregarCabecera($section, array $empresa): void
    {
        $section->addParagraph($empresa['nombre'], [
            'bold' => true,
            'size' => 16,
            'align' => 'center',
        ]);
        $section->addParagraph("RUC: {$empresa['ruc']}", ['align' => 'center']);
        $section->addParagraph($empresa['direccion'], ['align' => 'center']);
        if (isset($empresa['telefono'])) {
            $section->addParagraph("Tel: {$empresa['telefono']}", ['align' => 'center']);
        }
        $section->addParagraph('');
    }

    /**
     * Agregar celda de encabezado a tabla
     */
    private function agregarCeldaEncabezado($table, string $texto): void
    {
        $cell = $table->addCell($texto);
        $cell->setStyle([
            'bold' => true,
            'bgColor' => 'CCCCCC',
        ]);
    }
}
