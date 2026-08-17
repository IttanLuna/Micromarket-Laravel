<?php

namespace App\Services\DocumentGeneration;

use App\Models\Venta;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Document;

class DocumentService
{
    private PdfGenerator $pdfGenerator;
    private WordGenerator $wordGenerator;

    public function __construct()
    {
        $this->pdfGenerator = new PdfGenerator();
        $this->wordGenerator = new WordGenerator();
    }

    /**
     * Generar recibo de venta
     */
    public function generarRecibo(int $ventaId, string $formato = 'pdf'): mixed
    {
        $venta = Venta::with('detalles.producto', 'cliente')->findOrFail($ventaId);

        $empresa = [
            'nombre' => config('app.name', 'MICROMARKET S.A.C.'),
            'ruc' => config('app.ruc', '20512345678'),
            'direccion' => config('app.direccion', 'Av. Principal 123, Lima'),
            'telefono' => config('app.telefono', '(01) 555-1234'),
        ];

        $datos = [
            'venta' => $venta,
            'detalles' => $venta->detalles,
            'empresa' => $empresa,
        ];

        return $formato === 'pdf'
            ? $this->pdfGenerator->generarRecibo($datos)
            : $this->wordGenerator->generarRecibo($datos);
    }

    /**
     * Generar reporte de inventario
     */
    public function generarReporteInventario(array $productos, array $resumen, string $formato = 'pdf'): mixed
    {
        $datos = [
            'productos' => $productos,
            'resumen' => $resumen,
            'periodo' => now()->format('F Y'),
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ];

        return $formato === 'pdf'
            ? $this->pdfGenerator->generarReporteInventario($datos)
            : $this->wordGenerator->generarReporteInventario($datos);
    }

    /**
     * Generar contrato de proveedor
     */
    public function generarContrato(array $datos, string $formato = 'pdf'): mixed
    {
        return $formato === 'pdf'
            ? $this->pdfGenerator->generarContrato($datos)
            : $this->wordGenerator->generarContrato($datos);
    }

    /**
     * Generar carta formal
     */
    public function generarCarta(array $datos, string $formato = 'pdf'): mixed
    {
        return $formato === 'pdf'
            ? $this->pdfGenerator->generarCarta($datos)
            : $this->wordGenerator->generarCarta($datos);
    }

    /**
     * Generar certificado de trabajo
     */
    public function generarCertificado(array $datos, string $formato = 'pdf'): mixed
    {
        return $formato === 'pdf'
            ? $this->pdfGenerator->generarCertificado($datos)
            : $this->wordGenerator->generarCertificado($datos);
    }

    /**
     * Generar desde datos manuales
     */
    public function desdeDatosManuales(array $datos, string $tipo, string $formato = 'pdf'): mixed
    {
        return match ($tipo) {
            'recibo' => $this->generarRecibo($datos['venta_id'], $formato),
            'reporte_inventario' => $this->generarReporteInventario(
                $datos['productos'] ?? [],
                $datos['resumen'] ?? [],
                $formato
            ),
            'contrato' => $this->generarContrato($datos, $formato),
            'carta' => $this->generarCarta($datos, $formato),
            'certificado' => $this->generarCertificado($datos, $formato),
            default => throw new \InvalidArgumentException("Tipo de documento no válido: {$tipo}"),
        };
    }

    /**
     * Generar desde archivo CSV
     */
    public function desdeArchivoCsv(string $path, string $tipo, string $formato = 'pdf'): mixed
    {
        if (!file_exists($path)) {
            throw new \FileNotFoundException("Archivo no encontrado: {$path}");
        }

        $datos = $this->parsearCsv($path);

        return match ($tipo) {
            'inventario' => $this->generarReporteInventario(
                $datos['productos'] ?? [],
                $datos['resumen'] ?? [],
                $formato
            ),
            default => throw new \InvalidArgumentException("Tipo no válido para CSV: {$tipo}"),
        };
    }

    /**
     * Generar desde JSON
     */
    public function desdeJson(array $json, string $tipo, string $formato = 'pdf'): mixed
    {
        return match ($tipo) {
            'recibo' => $this->generarRecibo($json['venta_id'], $formato),
            'reporte' => $this->generarReporteInventario(
                $json['productos'] ?? [],
                $json['resumen'] ?? [],
                $formato
            ),
            'contrato' => $this->generarContrato($json, $formato),
            'carta' => $this->generarCarta($json, $formato),
            'certificado' => $this->generarCertificado($json, $formato),
            default => throw new \InvalidArgumentException("Tipo no válido para JSON: {$tipo}"),
        };
    }

    /**
     * Parsear archivo CSV
     */
    private function parsearCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $productos = [];
        $totalValor = 0;
        $stockBajo = 0;
        $sinStock = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $producto = array_combine($headers, $row);
            $productos[] = $producto;

            $stock = (int) ($producto['stock'] ?? 0);
            $precio = (float) ($producto['precio_venta'] ?? 0);
            $totalValor += $stock * $precio;

            if ($stock == 0) {
                $sinStock++;
            } elseif ($stock < ($producto['stock_minimo'] ?? 10)) {
                $stockBajo++;
            }
        }

        fclose($handle);

        return [
            'productos' => $productos,
            'resumen' => [
                'total_productos' => count($productos),
                'stock_bajo' => $stockBajo,
                'sin_stock' => $sinStock,
                'valor_total' => $totalValor,
            ],
        ];
    }
}
