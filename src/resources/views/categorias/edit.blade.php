<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Categoría - MicroMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">MicroMarket</h1>
                    <span class="text-sm">{{ Auth::user()->name ?? 'Invitado' }}</span>
                </div>
            </div>
        </header>

        <nav class="bg-blue-700 text-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex space-x-6 py-3">
                    <a href="{{ route('home') }}" class="hover:text-blue-200 font-medium">Dashboard</a>
                    <a href="{{ route('ventas.index') }}" class="hover:text-blue-200 font-medium">Ventas</a>
                    <a href="{{ route('productos.index') }}" class="hover:text-blue-200 font-medium">Productos</a>
                    <a href="{{ route('clientes.index') }}" class="hover:text-blue-200 font-medium">Clientes</a>
                    <a href="{{ route('proveedores.index') }}" class="hover:text-blue-200 font-medium">Proveedores</a>
                    <a href="{{ route('categorias.index') }}" class="text-blue-200 font-medium">Categorías</a>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Editar Categoría</h2>
                <a href="{{ route('categorias.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">← Volver</a>
            </div>

            <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea name="descripcion" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg">Actualizar Categoría</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
