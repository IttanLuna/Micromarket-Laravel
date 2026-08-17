<?php

namespace App\Http\Controllers;

use App\Services\DocumentGeneration\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {}

    /**
     * Generar documento
     */
    public function generar(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:recibo,reporte_inventario,contrato,carta,certificado',
            'formato' => 'required|in:pdf,word',
            'datos' => 'required|array',
        ]);

        try {
            $documento = $this->documentService->desdeDatosManuales(
                $validated['datos'],
                $validated['tipo'],
                $validated['formato']
            );

            $extension = $validated['formato'] === 'pdf' ? 'pdf' : 'docx';
            $filename = "{$validated['tipo']}-" . now()->timestamp . ".{$extension}";

            if ($validated['formato'] === 'pdf') {
                return $documento->download($filename);
            }

            return $this->wordGenerator->descargar($documento, $filename);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar documento: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar recibo de venta
     */
    public function recibo(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'formato' => 'required|in:pdf,word',
        ]);

        try {
            $documento = $this->documentService->generarRecibo(
                $request->venta_id,
                $request->formato
            );

            $extension = $request->formato === 'pdf' ? 'pdf' : 'docx';
            $filename = "recibo-" . now()->timestamp . ".{$extension}";

            if ($request->formato === 'pdf') {
                return $documento->download($filename);
            }

            return $this->wordGenerator->descargar($documento, $filename);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar recibo: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar reporte de inventario
     */
    public function reporteInventario(Request $request)
    {
        $request->validate([
            'formato' => 'required|in:pdf,word',
        ]);

        try {
            // Obtener datos del inventario
            $productos = \App\Models\Producto::with('categoria')
                ->stockBajo()
                ->get()
                ->map(function ($producto) {
                    return [
                        'nombre' => $producto->nombre,
                        'categoria' => $producto->categoria->nombre ?? '',
                        'stock' => $producto->stock,
                        'stock_minimo' => $producto->stock_minimo,
                        'precio_venta' => $producto->precio_venta,
                    ];
                });

            $resumen = [
                'total_productos' => \App\Models\Producto::count(),
                'stock_bajo' => \App\Models\Producto::stockBajo()->count(),
                'sin_stock' => \App\Models\Producto::sinStock()->count(),
                'valor_total' => \App\Models\Producto::sum(\DB::raw('stock * precio_venta')),
            ];

            $documento = $this->documentService->generarReporteInventario(
                $productos->toArray(),
                $resumen,
                $request->formato
            );

            $extension = $request->formato === 'pdf' ? 'pdf' : 'docx';
            $filename = "reporte-inventario-" . now()->timestamp . ".{$extension}";

            if ($request->formato === 'pdf') {
                return $documento->download($filename);
            }

            return $this->wordGenerator->descargar($documento, $filename);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar reporte: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar certificado de trabajo
     */
    public function certificado(Request $request)
    {
        $request->validate([
            'empleado.nombre' => 'required|string',
            'empleado.dni' => 'required|string',
            'empleado.cargo' => 'required|string',
            'empleado.fecha_inicio' => 'required|date',
            'empleado.remuneracion' => 'required|numeric|min:0',
            'formato' => 'required|in:pdf,word',
        ]);

        try {
            $datos = $request->only(['empleado']);
            $datos['empresa'] = [
                'nombre' => config('app.name', 'MICROMARKET S.A.C.'),
                'ruc' => config('app.ruc', '20512345678'),
                'direccion' => config('app.direccion', 'Av. Principal 123, Lima'),
            ];
            $datos['fecha_emision'] = now()->format('d \d\e F \d\e Y');
            $datos['firma_gerente'] = ['nombre' => 'Gerente General'];

            $documento = $this->documentService->generarCertificado(
                $datos,
                $request->formato
            );

            $extension = $request->formato === 'pdf' ? 'pdf' : 'docx';
            $filename = "certificado-" . now()->timestamp . ".{$extension}";

            if ($request->formato === 'pdf') {
                return $documento->download($filename);
            }

            return $this->wordGenerator->descargar($documento, $filename);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar certificado: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar desde CSV
     */
    public function desdeCsv(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:10240',
            'tipo' => 'required|in:inventario',
            'formato' => 'required|in:pdf,word',
        ]);

        try {
            $file = $request->file('archivo');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('imports', $filename);

            $documento = $this->documentService->desdeArchivoCsv(
                storage_path("app/imports/{$filename}"),
                $request->tipo,
                $request->formato
            );

            $extension = $request->formato === 'pdf' ? 'pdf' : 'docx';
            $outputFilename = "{$request->tipo}-" . now()->timestamp . ".{$extension}";

            if ($request->formato === 'pdf') {
                return $documento->download($outputFilename);
            }

            return $this->wordGenerator->descargar($documento, $outputFilename);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar desde CSV: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar desde JSON
     */
    public function desdeJson(Request $request)
    {
        $request->validate([
            'datos' => 'required|array',
            'tipo' => 'required|in:recibo,reporte,contrato,carta,certificado',
            'formato' => 'required|in:pdf,word',
        ]);

        try {
            $documento = $this->documentService->desdeJson(
                $request->datos,
                $request->tipo,
                $request->formato
            );

            $extension = $request->formato === 'pdf' ? 'pdf' : 'docx';
            $filename = "{$request->tipo}-" . now()->timestamp . ".{$extension}";

            if ($request->formato === 'pdf') {
                return $documento->download($filename);
            }

            return $this->wordGenerator->descargar($documento, $filename);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al generar desde JSON: ' . $e->getMessage()]);
        }
    }
}
