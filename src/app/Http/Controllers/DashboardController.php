<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Categoria;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::count();
        $totalClientes = Cliente::count();
        $ventasHoy = Venta::hoy()->where('estado', 'completada')->count();
        $ingresosHoy = Venta::hoy()->where('estado', 'completada')->sum('total');
        $productosStockBajo = Producto::whereColumn('stock', '<', 'stock_minimo')
            ->where('stock', '>', 0)
            ->get();
        $productosSinStock = Producto::sinStock()->get();

        return view('dashboard', compact(
            'totalProductos',
            'totalClientes',
            'ventasHoy',
            'ingresosHoy',
            'productosStockBajo',
            'productosSinStock'
        ));
    }

    public function ventasMensuales(Request $request)
    {
        $year = $request->input('year', now()->year);
        $filtro = $request->input('filtro', 'general');
        $categoriaId = $request->input('categoria_id');
        $productoId = $request->input('producto_id');

        $meses = collect(range(1, 12))->map(function ($mes) use ($year, $filtro, $categoriaId, $productoId) {
            $query = Venta::whereYear('created_at', $year)
                ->whereMonth('created_at', $mes)
                ->where('estado', 'completada');

            if ($filtro === 'categoria' && $categoriaId) {
                $query->whereHas('detalles.producto', function ($q) use ($categoriaId) {
                    $q->where('categoria_id', $categoriaId);
                });
            } elseif ($filtro === 'producto' && $productoId) {
                $query->whereHas('detalles', function ($q) use ($productoId) {
                    $q->where('producto_id', $productoId);
                });
            }

            return [
                'mes' => $mes,
                'nombre' => now()->setMonth($mes)->format('M'),
                'total' => (float) $query->sum('total'),
                'cantidad' => $query->count(),
            ];
        });

        $categorias = Categoria::activos()->get();
        $productos = Producto::where('activo', true)->get();

        return response()->json([
            'meses' => $meses->values(),
            'categorias' => $categorias,
            'productos' => $productos,
        ]);
    }
}
