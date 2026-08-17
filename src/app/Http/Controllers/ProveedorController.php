<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $proveedores = Proveedor::when($request->search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%");
        })->latest()->paginate(20);

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'ruc' => 'required|string|max:11|unique:proveedores,ruc',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'contacto' => 'nullable|string|max:100',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado correctamente');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'ruc' => 'required|string|max:11|unique:proveedores,ruc,' . $proveedor->id,
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'contacto' => 'nullable|string|max:100',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente');
    }
}
