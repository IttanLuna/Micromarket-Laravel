<?php

namespace App\Http\Controllers;

use App\Models\{
    Producto,
    Categoria,
};
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->categoria, function ($query, $categoria) {
                $query->where('categoria_id', $categoria);
            })
            ->latest()
            ->paginate(20);

        $categorias = Categoria::activos()->orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::activos()->orderBy('nombre')->get();

        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'sku' => 'required|string|max:50|unique:productos,sku',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function show(Producto $producto)
    {
        $producto->load('categoria');

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::activos()->orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'sku' => 'required|string|max:50|unique:productos,sku,' . $producto->id,
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
