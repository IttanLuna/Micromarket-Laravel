<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Venta - MicroMarket</title>
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
                <h2 class="text-3xl font-bold text-gray-800">Nueva Venta</h2>
                <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    ← Volver
                </a>
            </div>

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Cliente -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Datos del Cliente</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                            <select name="cliente_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar cliente...</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->apellido }} - DNI: {{ $cliente->dni }}
                                </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Pago -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Datos de Pago</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago *</label>
                            <select name="metodo_pago" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                        </div>
                        <div class="mb-4" id="montoEntregadoDiv">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Monto Entregado *</label>
                            <input type="number" name="monto_entregado" step="0.01" min="0" value="{{ old('monto_entregado') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Productos</h3>
                        <button type="button" onclick="agregarProducto()"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            + Agregar Producto
                        </button>
                    </div>

                    <div id="productosContainer">
                        <!-- Productos se agregarán aquí -->
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-end space-x-4 text-lg">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-bold" id="subtotal">Bs. 0.00</span>
                        </div>
                        <div class="flex justify-end space-x-4 text-lg">
                            <span class="text-gray-600">IGV (18%):</span>
                            <span class="font-bold" id="igv">Bs. 0.00</span>
                        </div>
                        <div class="flex justify-end space-x-4 text-xl border-t pt-2 mt-2">
                            <span class="text-gray-800 font-bold">TOTAL:</span>
                            <span class="font-bold text-green-600" id="total">Bs. 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg text-lg">
                        Registrar Venta
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        const productos = @json($productos);
        let productoIndex = 0;

        function agregarProducto() {
            const container = document.getElementById('productosContainer');
            const html = `
                <div class="grid grid-cols-12 gap-4 mb-4 items-end" id="producto-${productoIndex}">
                    <div class="col-span-5">
                        <select name="productos[${productoIndex}][id]" required onchange="actualizarPrecio(${productoIndex})"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Seleccionar...</option>
                            ${productos.map(p => `<option value="${p.id}" data-precio="${p.precio_venta}">${p.nombre} - Bs. ${p.precio_venta}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="productos[${productoIndex}][cantidad]" min="1" value="1" required
                            onchange="calcularSubtotal(${productoIndex})"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Cantidad">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="productos[${productoIndex}][precio]" step="0.01" readonly
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100" placeholder="Precio">
                    </div>
                    <div class="col-span-2">
                        <button type="button" onclick="eliminarProducto(${productoIndex})"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                            X
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            productoIndex++;
        }

        function eliminarProducto(index) {
            document.getElementById(`producto-${index}`).remove();
            calcularTotales();
        }

        function actualizarPrecio(index) {
            const select = document.querySelector(`[name="productos[${index}][id]"]`);
            const precioInput = document.querySelector(`[name="productos[${index}][precio]"]`);
            const option = select.options[select.selectedIndex];
            precioInput.value = option.dataset.precio || '';
            calcularSubtotal(index);
        }

        function calcularSubtotal(index) {
            calcularTotales();
        }

        function calcularTotales() {
            let subtotal = 0;
            document.querySelectorAll('[name^="productos"][name$="[id]"]').forEach((select, i) => {
                const cantidad = document.querySelector(`[name="productos[${i}][cantidad]"]`);
                const precio = document.querySelector(`[name="productos[${i}][precio]"]`);
                if (cantidad && precio && select.value) {
                    subtotal += parseFloat(cantidad.value) * parseFloat(precio.value);
                }
            });

            const igv = subtotal * 0.18;
            const total = subtotal + igv;

            document.getElementById('subtotal').textContent = `Bs. ${subtotal.toFixed(2)}`;
            document.getElementById('igv').textContent = `Bs. ${igv.toFixed(2)}`;
            document.getElementById('total').textContent = `Bs. ${total.toFixed(2)}`;
        }

        // Agregar primer producto
        agregarProducto();
    </script>
</body>
</html>
