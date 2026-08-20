<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MicroMarket - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1); }
        .action-btn { transition: all 0.3s ease; }
        .action-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); }
        .action-btn:active { transform: translateY(0); }
        .nav-link { position: relative; transition: color 0.3s ease; }
        .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: white; transition: width 0.3s ease; }
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #bfdbfe; }
        .table-row { transition: background-color 0.2s ease; }
        .table-row:hover { background-color: #eff6ff; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); }
        .btn-logout { transition: all 0.3s ease; }
        .btn-logout:hover { background-color: #dc2626; transform: scale(1.05); }
        .select-focus { transition: all 0.3s ease; }
        .select-focus:focus { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15); }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 via-blue-50 to-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">MicroMarket</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">{{ Auth::user()->name }}</span>
                        <span class="bg-blue-500 px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst(Auth::user()->role) }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-logout bg-red-500 px-3 py-1 rounded text-xs flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
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
                    <a href="{{ route('home') }}" class="nav-link text-blue-200 font-medium">Dashboard</a>
                    <a href="{{ route('ventas.index') }}" class="nav-link font-medium">Ventas</a>
                    <a href="{{ route('productos.index') }}" class="nav-link font-medium">Productos</a>
                    <a href="{{ route('clientes.index') }}" class="nav-link font-medium">Clientes</a>
                    <a href="{{ route('proveedores.index') }}" class="nav-link font-medium">Proveedores</a>
                    <a href="{{ route('categorias.index') }}" class="nav-link font-medium">Categorías</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 animate-fade-in-up">Bienvenido, {{ Auth::user()->name }}</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white rounded-xl shadow-md p-6 animate-fade-in-up delay-1">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Productos</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalProductos }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-md p-6 animate-fade-in-up delay-2">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Clientes</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalClientes }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-md p-6 animate-fade-in-up delay-3">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Ventas Hoy</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $ventasHoy }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-md p-6 animate-fade-in-up delay-4">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Ingresos Hoy</p>
                            <p class="text-2xl font-bold text-gray-800">Bs. {{ number_format($ingresosHoy, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-hover bg-white rounded-xl shadow-md p-6 mb-8 animate-fade-in-up">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Acciones Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('ventas.create') }}" class="action-btn bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-center flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Venta
                    </a>
                    <a href="{{ route('productos.create') }}" class="action-btn bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-center flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Agregar Producto
                    </a>
                    <a href="{{ route('clientes.create') }}" class="action-btn bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg text-center flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Nuevo Cliente
                    </a>
                </div>
            </div>

            <!-- Gráfica de Ventas Mensuales -->
            <div class="card-hover bg-white rounded-xl shadow-md p-6 mb-8 animate-fade-in-up">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Ventas Mensuales</h3>
                <div class="flex flex-wrap gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <select id="chartYear" class="select-focus border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filtro</label>
                        <select id="chartFiltro" class="select-focus border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="general">General (Todo)</option>
                            <option value="categoria">Por Categoría</option>
                            <option value="producto">Por Producto</option>
                        </select>
                    </div>
                    <div id="categoriaFilter" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <select id="chartCategoria" class="select-focus border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div id="productoFilter" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                        <select id="chartProducto" class="select-focus border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                </div>
                <div class="relative" style="height: 350px;">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="card-hover bg-white rounded-xl shadow-md p-6 animate-fade-in-up">
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
                            @php
                                $todosStockBajo = $productosStockBajo->merge($productosSinStock);
                            @endphp
                            @forelse($todosStockBajo as $producto)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $producto->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $producto->stock }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $producto->stock_minimo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($producto->stock == 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Sin Stock
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Bajo
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay productos con stock bajo
                                    </td>
                                </tr>
                            @endforelse
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

    <script>
        const ctx = document.getElementById('ventasChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Ventas (Bs.)',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return 'Bs. ' + ctx.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Bs. ' + value;
                            }
                        }
                    }
                }
            }
        });

        function cargarGrafica() {
            const year = document.getElementById('chartYear').value;
            const filtro = document.getElementById('chartFiltro').value;
            const categoriaId = document.getElementById('chartCategoria').value;
            const productoId = document.getElementById('chartProducto').value;

            let url = `{{ route('dashboard.ventas-mensuales') }}?year=${year}&filtro=${filtro}`;
            if (categoriaId) url += `&categoria_id=${categoriaId}`;
            if (productoId) url += `&producto_id=${productoId}`;

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    chart.data.labels = data.meses.map(m => m.nombre);
                    chart.data.datasets[0].data = data.meses.map(m => m.total);
                    chart.update();

                    const catSelect = document.getElementById('chartCategoria');
                    const prodSelect = document.getElementById('chartProducto');

                    catSelect.innerHTML = '<option value="">Seleccionar...</option>';
                    data.categorias.forEach(c => {
                        catSelect.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
                    });

                    prodSelect.innerHTML = '<option value="">Seleccionar...</option>';
                    data.productos.forEach(p => {
                        prodSelect.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
                    });
                });
        }

        document.getElementById('chartYear').addEventListener('change', cargarGrafica);
        document.getElementById('chartFiltro').addEventListener('change', function() {
            const filtro = this.value;
            document.getElementById('categoriaFilter').classList.toggle('hidden', filtro !== 'categoria');
            document.getElementById('productoFilter').classList.toggle('hidden', filtro !== 'producto');
            cargarGrafica();
        });
        document.getElementById('chartCategoria').addEventListener('change', cargarGrafica);
        document.getElementById('chartProducto').addEventListener('change', cargarGrafica);

        cargarGrafica();
    </script>
</body>
</html>
