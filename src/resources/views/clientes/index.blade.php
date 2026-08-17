<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clientes - MicroMarket</title>
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
                    <a href="{{ route('clientes.index') }}" class="text-blue-200 font-medium">Clientes</a>
                    <a href="{{ route('proveedores.index') }}" class="hover:text-blue-200 font-medium">Proveedores</a>
                    <a href="{{ route('categorias.index') }}" class="hover:text-blue-200 font-medium">Categorías</a>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Clientes</h2>
                <a href="{{ route('clientes.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    + Nuevo Cliente
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form action="{{ route('clientes.index') }}" method="GET" class="flex space-x-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, apellido o DNI..."
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">Buscar</button>
                </form>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($clientes as $cliente)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $cliente->dni }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->telefono ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('clientes.edit', $cliente) }}" class="text-yellow-600 hover:text-yellow-800">Editar</a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Eliminar cliente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay clientes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $clientes->links() }}</div>
        </main>
    </div>
</body>
</html>
