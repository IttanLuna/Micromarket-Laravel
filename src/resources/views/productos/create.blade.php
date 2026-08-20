<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Producto - MicroMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .btn-animated {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .btn-animated:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .btn-animated:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .nav-link {
            position: relative;
            transition: color 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #bfdbfe;
            transition: width 0.3s ease, left 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }
        .form-input-animated {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-input-animated:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }
        .back-btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .save-btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
        }
        .save-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
                            <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs btn-animated">
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
                    <a href="{{ route('home') }}" class="nav-link font-medium">Dashboard</a>
                    <a href="{{ route('ventas.index') }}" class="nav-link font-medium">Ventas</a>
                    <a href="{{ route('productos.index') }}" class="nav-link text-blue-200 font-medium">Productos</a>
                    <a href="{{ route('clientes.index') }}" class="nav-link font-medium">Clientes</a>
                    <a href="{{ route('proveedores.index') }}" class="nav-link font-medium">Proveedores</a>
                    <a href="{{ route('categorias.index') }}" class="nav-link font-medium">Categorías</a>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 py-8 animate-fade-in-up">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Nuevo Producto</h2>
                <a href="{{ route('productos.index') }}" class="back-btn bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Volver</span>
                </a>
            </div>

            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Información General</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                    class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                                @error('nombre') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                                <input type="text" name="sku" value="{{ old('sku') }}" required
                                    class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                                @error('sku') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                                <select name="categoria_id" required class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                                    <option value="">Seleccionar...</option>
                                    @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                <textarea name="descripcion" rows="3" class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Precios y Stock</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Precio Compra (Bs.) *</label>
                                <input type="number" name="precio_compra" step="0.01" min="0" value="{{ old('precio_compra') }}" required
                                    class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Precio Venta (Bs.) *</label>
                                <input type="number" name="precio_venta" step="0.01" min="0" value="{{ old('precio_venta') }}" required
                                    class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Actual *</label>
                                <input type="number" name="stock" min="0" value="{{ old('stock', 0) }}" required
                                    class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo *</label>
                                <input type="number" name="stock_minimo" min="0" value="{{ old('stock_minimo', 10) }}" required
                                    class="form-input-animated w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="save-btn bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Guardar Producto</span>
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
