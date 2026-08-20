<?php

use App\Http\Controllers\{
    AuthController,
    DashboardController,
    DocumentController,
    VentaController,
    ProductoController,
    ClienteController,
    ProveedorController,
    CategoriaController,
};
use Illuminate\Support\Facades\Route;

// Healthcheck
Route::get('/health', fn() => response()->json(['status' => 'ok']))->name('health');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard/ventas-mensuales', [DashboardController::class, 'ventasMensuales'])->name('dashboard.ventas-mensuales');

    // Rutas para documentos
    Route::prefix('documentos')->name('documentos.')->group(function () {
        Route::get('/recibo', [DocumentController::class, 'recibo'])->name('recibo');
        Route::post('/generar', [DocumentController::class, 'generar'])->name('generar');
        Route::post('/reporte-inventario', [DocumentController::class, 'reporteInventario'])->name('reporte-inventario');
        Route::post('/certificado', [DocumentController::class, 'certificado'])->name('certificado');
        Route::post('/desde-csv', [DocumentController::class, 'desdeCsv'])->name('desde-csv');
        Route::post('/desde-json', [DocumentController::class, 'desdeJson'])->name('desde-json');
    });

    // Rutas para ventas
    Route::resource('ventas', VentaController::class)->except(['edit', 'update']);

    // Rutas para productos
    Route::resource('productos', ProductoController::class);

    // Rutas para clientes
    Route::resource('clientes', ClienteController::class);

    // Rutas para proveedores
    Route::resource('proveedores', ProveedorController::class);

    // Rutas para categorías
    Route::resource('categorias', CategoriaController::class);
});
