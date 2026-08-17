<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Venta {{ $venta->numero }} - MicroMarket</title>
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
                <h2 class="text-3xl font-bold text-gray-800">Venta {{ $venta->numero }}</h2>
                <div class="flex space-x-4">
                    <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        ← Volver
                    </a>
                    <a href="{{ route('documentos.recibo') }}?venta_id={{ $venta->id }}&formato=pdf" target="_blank"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        PDF
                    </a>
                    <a href="{{ route('documentos.recibo') }}?venta_id={{ $venta->id }}&formato=word" target="_blank"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
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
                <!-- Info Venta -->
                <div class="bg-white rounded-lg shadow-md p-6">
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

                <!-- Info Cliente -->
                <div class="bg-white rounded-lg shadow-md p-6">
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

            <!-- Detalles -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
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

            <!-- Pago -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
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
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Anular Venta
                    </button>
                </form>
            </div>
            @endif
        </main>
    </div>
</body>
</html>
