---
name: laravel-routing
description: "Crear rutas, controladores y middleware en Laravel para el MicroMarket. Incluye rutas API, controladores resource y middleware de autenticación."
---

# Laravel Routing Skill

Crea rutas y controladores para el MicroMarket.

## Rutas del Proyecto

```php
<?php

// routes/web.php
use App\Http\Controllers\{
    VentaController,
    ProductoController,
    ClienteController,
    DocumentController,
    ReporteController,
};
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Ventas
Route::resource('ventas', VentaController::class)->except(['show']);
Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');

// Productos
Route::resource('productos', ProductoController::class);

// Clientes
Route::resource('clientes', ClienteController::class)->except(['show']);

// Documentos
Route::prefix('documentos')->name('documentos.')->group(function () {
    Route::post('generar', [DocumentController::class, 'generar'])->name('generar');
    Route::get('descargar/{hash}', [DocumentController::class, 'descargar'])->name('descargar');
    Route::post('desde-csv', [DocumentController::class, 'desdeCsv'])->name('desde-csv');
    Route::post('desde-json', [DocumentController::class, 'desdeJson'])->name('desde-json');
});

// Reportes
Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('inventario', [ReporteController::class, 'inventario'])->name('inventario');
    Route::get('ventas', [ReporteController::class, 'ventas'])->name('ventas');
});
```

```php
<?php

// routes/api.php
use App\Http\Controllers\Api\{
    VentaApiController,
    ProductoApiController,
    ClienteApiController,
    DocumentApiController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Ventas
    Route::apiResource('ventas', VentaApiController::class);
    
    // Productos
    Route::apiResource('productos', ProductoApiController::class);
    
    // Clientes
    Route::apiResource('clientes', ClienteApiController::class);
    
    // Documentos
    Route::post('documents/generate', [DocumentApiController::class, 'generate']);
    Route::get('documents/download/{hash}', [DocumentApiController::class, 'download']);
});
```

## Controladores

### VentaController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Http\Requests\StoreVentaRequest;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $ventas = Venta::with('cliente')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::conStock()->orderBy('nombre')->get();
        
        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(StoreVentaRequest $request)
    {
        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'user_id' => auth()->id(),
            'metodo_pago' => $request->metodo_pago,
            'monto_entregado' => $request->monto_entregado,
        ]);

        foreach ($request->productos as $producto) {
            $venta->detalles()->create([
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio'],
            ]);
        }

        $venta->calcularTotales();

        return redirect()->route('ventas.show', $venta)
            ->with('success', 'Venta registrada correctamente');
    }

    public function show(Venta $venta)
    {
        $venta->load('detalles.producto', 'cliente');
        
        return view('ventas.show', compact('venta'));
    }
}
```

### DocumentController

```php
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

    public function generar(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:recibo,reporte,contrato,carta,certificado',
            'formato' => 'required|in:pdf,word',
            'datos' => 'required|array',
        ]);

        $documento = match($validated['tipo']) {
            'recibo' => $this->documentService->generarRecibo(
                $validated['datos']['venta_id'],
                $validated['formato']
            ),
            'reporte' => $this->documentService->desdeDatosManuales(
                $validated['datos'],
                'reporte'
            ),
            'contrato' => $this->documentService->generarContrato(
                $validated['datos'],
                $validated['formato']
            ),
            'certificado' => $this->documentService->generarCertificado(
                $validated['datos'],
                $validated['formato']
            ),
        };

        $extension = $validated['formato'] === 'pdf' ? 'pdf' : 'docx';
        $filename = "{$validated['tipo']}-" . now()->timestamp . ".{$extension}";

        return $documento->download($filename);
    }

    public function descargar(string $hash)
    {
        $path = storage_path("app/documents/{$hash}");
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function desdeCsv(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:10240',
            'tipo' => 'required|in:inventario,productos',
        ]);

        $file = $request->file('archivo');
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->storeAs('imports', $filename);

        $documento = $this->documentService->desdeArchivoCsv(
            storage_path("app/imports/{$filename}"),
            $request->tipo
        );

        return $documento->download("{$request->tipo}-{$filename}.pdf");
    }

    public function desdeJson(Request $request)
    {
        $request->validate([
            'datos' => 'required|array',
            'tipo' => 'required|in:reporte,ventas',
        ]);

        $documento = $this->documentService->desdeJson(
            $request->datos,
            $request->tipo
        );

        return $documento->download("{$request->tipo}-" . now()->timestamp . ".pdf");
    }
}
```

## Form Requests

### StoreVentaRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'monto_entregado' => 'required_if:metodo_pago,efectivo|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'productos.required' => 'Debe agregar al menos un producto',
            'productos.min' => 'Debe agregar al menos un producto',
        ];
    }
}
```

## Middleware de Roles

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
```

## Uso en Rutas

```php
// Solo admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('productos', ProductoController::class);
});

// Admin y vendedor
Route::middleware(['auth', 'role:admin,vendedor'])->group(function () {
    Route::resource('ventas', VentaController::class);
});
```
