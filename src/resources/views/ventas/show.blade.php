<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Venta {{ $venta->numero }} - MicroMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        nav a {
            position: relative;
        }
        nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }
        nav a:hover::after {
            width: 100%;
        }
        .action-btn {
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-r from-gray-100 via-blue-50 to-gray-100">
    <div class="min-h-screen">
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">MicroMarket</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">{{ Auth::user()->name ?? 'Invitado' }}</span>
                        <span class="bg-blue-500 px-3 py-1 rounded text-xs">{{ ucfirst(Auth::user()->role ?? '') }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs btn-hover">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

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

        <main class="max-w-7xl mx-auto px-4 py-8 animate-fade-in-up">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Venta {{ $venta->numero }}</h2>
                <div class="flex space-x-4">
                    <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded btn-hover flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver
                    </a>
                    <a href="{{ route('documentos.recibo') }}?venta_id={{ $venta->id }}&formato=pdf" target="_blank"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded action-btn flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        PDF
                    </a>
                    <a href="{{ route('documentos.recibo') }}?venta_id={{ $venta->id }}&formato=word" target="_blank"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded action-btn flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Word
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Información de la Venta</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Número:</span>
                            <span class="font-bold">{{ $venta->numero }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha:</span>
                            <span>{{ $venta->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estado:</span>
                            @if($venta->estado == 'completada')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completada</span>
                            @elseif($venta->estado == 'anulada')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Anulada</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vendedor:</span>
                            <span>{{ $venta->user->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Datos del Cliente</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nombre:</span>
                            <span class="font-bold">{{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">DNI:</span>
                            <span>{{ $venta->cliente->dni }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Teléfono:</span>
                            <span>{{ $venta->cliente->telefono ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mt-6 card-hover">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Productos</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">P. Unitario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($venta->detalles as $index => $detalle)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $detalle->producto->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $detalle->cantidad }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-end space-x-4 text-lg">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-bold">Bs. {{ number_format($venta->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-end space-x-4 text-lg">
                        <span class="text-gray-600">IGV (18%):</span>
                        <span class="font-bold">Bs. {{ number_format($venta->igv, 2) }}</span>
                    </div>
                    <div class="flex justify-end space-x-4 text-xl border-t pt-2 mt-2">
                        <span class="text-gray-800 font-bold">TOTAL:</span>
                        <span class="font-bold text-green-600">Bs. {{ number_format($venta->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mt-6 card-hover">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Pago</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Método de pago:</span>
                        <span class="font-bold">{{ ucfirst($venta->metodo_pago) }}</span>
                    </div>
                    @if($venta->monto_entregado)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Monto entregado:</span>
                        <span>Bs. {{ number_format($venta->monto_entregado, 2) }}</span>
                    </div>
                    @endif
                    @if($venta->vuelto)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Vuelto:</span>
                        <span class="text-green-600 font-bold">Bs. {{ number_format($venta->vuelto, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($venta->estado == 'completada')
            <div class="mt-6">
                <form action="{{ route('ventas.destroy', $venta) }}" method="POST"
                    onsubmit="return confirm('¿Estás seguro de anular esta venta?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded action-btn flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Anular Venta
                    </button>
                </form>
            </div>
            @endif
        </main>
    </div>
</body>
</html>