<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MicroMarket - Dashboard</title>
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
                        <span class="text-sm">{{ Auth::user()->name }}</span>
                        <span class="bg-blue-500 px-3 py-1 rounded text-xs">{{ ucfirst(Auth::user()->role) }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="bg-blue-700 text-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex space-x-6 py-3">
                    <a href="{{ route('home') }}" class="text-blue-200 font-medium">Dashboard</a>
                    <a href="{{ route('ventas.index') }}" class="hover:text-blue-200 font-medium">Ventas</a>
                    <a href="{{ route('productos.index') }}" class="hover:text-blue-200 font-medium">Productos</a>
                    <a href="{{ route('clientes.index') }}" class="hover:text-blue-200 font-medium">Clientes</a>
                    <a href="{{ route('proveedores.index') }}" class="hover:text-blue-200 font-medium">Proveedores</a>
                    <a href="{{ route('categorias.index') }}" class="hover:text-blue-200 font-medium">Categorías</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Bienvenido, {{ Auth::user()->name }}</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Productos</p>
                            <p class="text-2xl font-bold text-gray-800">10</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Clientes</p>
                            <p class="text-2xl font-bold text-gray-800">3</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Ventas Hoy</p>
                            <p class="text-2xl font-bold text-gray-800">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Ingresos Hoy</p>
                            <p class="text-2xl font-bold text-gray-800">Bs. 0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Acciones Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('ventas.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        Nueva Venta
                    </a>
                    <a href="{{ route('productos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        Agregar Producto
                    </a>
                    <a href="{{ route('clientes.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        Nuevo Cliente
                    </a>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Productos con Stock Bajo</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mínimo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">Galletas Vainilla</td>
                                <td class="px-6 py-4 whitespace-nowrap">5</td>
                                <td class="px-6 py-4 whitespace-nowrap">20</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Bajo
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">Papas Fritas 100g</td>
                                <td class="px-6 py-4 whitespace-nowrap">0</td>
                                <td class="px-6 py-4 whitespace-nowrap">15</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Sin Stock
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p>MicroMarket S.A. - RUC: 123456789</p>
                <p class="text-sm text-gray-400">Av. Principal 123, La Paz, Bolivia</p>
            </div>
        </footer>
    </div>
</body>
</html>
