<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ventas - MicroMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">MicroMarket</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">{{ Auth::user()->name ?? 'Invitado' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="bg-blue-700 text-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex space-x-6 py-3">
                    <a href="{{ route('home') }}" class="hover:text-blue-200 font-medium">Dashboard</a>
                    <a href="{{ route('ventas.index') }}" class="text-blue-200 font-medium">Ventas</a>
                    <a href="{{ route('productos.index') }}" class="hover:text-blue-200 font-medium">Productos</a>
                    <a href="{{ route('clientes.index') }}" class="hover:text-blue-200 font-medium">Clientes</a>
                    <a href="{{ route('proveedores.index') }}" class="hover:text-blue-200 font-medium">Proveedores</a>
                    <a href="{{ route('categorias.index') }}" class="hover:text-blue-200 font-medium">Categorías</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Ventas</h2>
                <a href="{{ route('ventas.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    + Nueva Venta
                </a>
            </div>

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form action="{{ route('ventas.index') }}" method="GET">
                    <div class="flex space-x-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por cliente o número..."
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($ventas as $venta)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $venta->numero }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $venta->cliente->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">Bs. {{ number_format($venta->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($venta->estado == 'completada')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completada</span>
                                @elseif($venta->estado == 'anulada')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Anulada</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('ventas.show', $venta) }}" class="text-blue-600 hover:text-blue-800">Ver</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay ventas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $ventas->links() }}
            </div>
        </main>
    </div>
</body>
</html>
