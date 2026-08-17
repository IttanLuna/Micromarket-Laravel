<?php

namespace App\Http\Controllers;

use App\Models\{
    Venta,
    Cliente,
    Producto,
    VentaDetalle,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::with('cliente')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%");
                })
                ->orWhere('numero', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $productos = Producto::conStock()->orderBy('nombre')->get();

        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'monto_entregado' => 'required_if:metodo_pago,efectivo|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => auth()->id(),
                'metodo_pago' => $request->metodo_pago,
                'monto_entregado' => $request->monto_entregado,
            ]);

            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['id']);

                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $producto->precio_venta,
                ]);

                $producto->reducirStock($productoData['cantidad']);
            }

            $venta->calcularTotales();

            DB::commit();

            return redirect()->route('ventas.show', $venta)
                ->with('success', 'Venta registrada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar venta: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $venta->load('detalles.producto', 'cliente', 'user');

        return view('ventas.show', compact('venta'));
    }

    public function destroy(Venta $venta)
    {
        DB::beginTransaction();

        try {
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->aumentarStock($detalle->cantidad);
            }

            $venta->update(['estado' => 'anulada']);

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta anulada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al anular venta: ' . $e->getMessage());
        }
    }
}
