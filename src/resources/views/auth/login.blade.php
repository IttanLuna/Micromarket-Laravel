<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - MicroMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600">MicroMarket</h1>
            <p class="text-gray-500 mt-2">Sistema de Gestión de Ventas</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h2>

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="admin@micromarket.com">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Regístrate</a>
                </p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-bold text-blue-800 mb-2">Credenciales de prueba:</h3>
            <div class="text-xs text-blue-700 space-y-1">
                <p><strong>Admin:</strong> admin@micromarket.com</p>
                <p><strong>Vendedor:</strong> maria@micromarket.com</p>
                <p><strong>Contraseña:</strong> password</p>
            </div>
        </div>
    </div>
</body>
</html>
