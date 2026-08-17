<?php

namespace Database\Seeders;

use App\Models\{
    User,
    Cliente,
    Proveedor,
    Categoria,
    Producto,
};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Crear usuarios
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@micromarket.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $vendedor = User::create([
            'name' => 'María López',
            'email' => 'maria@micromarket.com',
            'password' => Hash::make('password'),
            'role' => 'vendedor',
        ]);

        // Crear categorías
        $bebidas = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Bebidas en general']);
        $lacteos = Categoria::create(['nombre' => 'Lácteos', 'descripcion' => 'Productos lácteos']);
        $panaderia = Categoria::create(['nombre' => 'Panadería', 'descripcion' => 'Productos de panadería']);
        $abarrotes = Categoria::create(['nombre' => 'Abarrotes', 'descripcion' => 'Productos básicos']);
        $snacks = Categoria::create(['nombre' => 'Snacks', 'descripcion' => 'Snacks y botanas']);

        // Crear productos (precios en Bolivianos)
        Producto::create([
            'categoria_id' => $bebidas->id,
            'nombre' => 'Agua Mineral 500ml',
            'sku' => 'BEB-001',
            'precio_compra' => 1.00,
            'precio_venta' => 2.00,
            'stock' => 100,
            'stock_minimo' => 30,
        ]);

        Producto::create([
            'categoria_id' => $bebidas->id,
            'nombre' => 'Gaseosa Cola 500ml',
            'sku' => 'BEB-002',
            'precio_compra' => 2.50,
            'precio_venta' => 4.50,
            'stock' => 50,
            'stock_minimo' => 20,
        ]);

        Producto::create([
            'categoria_id' => $lacteos->id,
            'nombre' => 'Leche Entera 1L',
            'sku' => 'LAC-001',
            'precio_compra' => 4.00,
            'precio_venta' => 6.50,
            'stock' => 40,
            'stock_minimo' => 15,
        ]);

        Producto::create([
            'categoria_id' => $lacteos->id,
            'nombre' => 'Yogurt Natural 500g',
            'sku' => 'LAC-002',
            'precio_compra' => 3.50,
            'precio_venta' => 6.00,
            'stock' => 25,
            'stock_minimo' => 10,
        ]);

        Producto::create([
            'categoria_id' => $panaderia->id,
            'nombre' => 'Pan Integral',
            'sku' => 'PAN-001',
            'precio_compra' => 2.00,
            'precio_venta' => 3.50,
            'stock' => 30,
            'stock_minimo' => 15,
        ]);

        Producto::create([
            'categoria_id' => $abarrotes->id,
            'nombre' => 'Arroz 5kg',
            'sku' => 'ABA-001',
            'precio_compra' => 18.00,
            'precio_venta' => 28.00,
            'stock' => 20,
            'stock_minimo' => 10,
        ]);

        Producto::create([
            'categoria_id' => $abarrotes->id,
            'nombre' => 'Aceite Vegetal 1L',
            'sku' => 'ABA-002',
            'precio_compra' => 10.00,
            'precio_venta' => 15.00,
            'stock' => 35,
            'stock_minimo' => 15,
        ]);

        Producto::create([
            'categoria_id' => $abarrotes->id,
            'nombre' => 'Azúcar 1kg',
            'sku' => 'ABA-003',
            'precio_compra' => 5.00,
            'precio_venta' => 8.00,
            'stock' => 45,
            'stock_minimo' => 20,
        ]);

        Producto::create([
            'categoria_id' => $snacks->id,
            'nombre' => 'Galletas Vainilla',
            'sku' => 'SNA-001',
            'precio_compra' => 2.50,
            'precio_venta' => 4.00,
            'stock' => 5,
            'stock_minimo' => 20,
        ]);

        Producto::create([
            'categoria_id' => $snacks->id,
            'nombre' => 'Papas Fritas 100g',
            'sku' => 'SNA-002',
            'precio_compra' => 3.50,
            'precio_venta' => 6.00,
            'stock' => 0,
            'stock_minimo' => 15,
        ]);

        // Crear clientes
        Cliente::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez García',
            'dni' => '45678901',
            'email' => 'juan@email.com',
            'telefono' => '999888777',
            'direccion' => 'Av. Los Olivos 123',
        ]);

        Cliente::create([
            'nombre' => 'María',
            'apellido' => 'González López',
            'dni' => '78945612',
            'email' => 'maria.g@email.com',
            'telefono' => '988777666',
            'direccion' => 'Jr. Lima 456',
        ]);

        Cliente::create([
            'nombre' => 'Carlos',
            'apellido' => 'Mendoza Torres',
            'dni' => '12345678',
            'email' => 'carlos@email.com',
            'telefono' => '977666555',
            'direccion' => 'Calle Sol 789',
        ]);

        // Crear proveedores
        Proveedor::create([
            'nombre' => 'Distribuidora ABC',
            'ruc' => '20123456789',
            'email' => 'ventas@abc.com',
            'telefono' => '(01) 5551111',
            'direccion' => 'Av. Industrial 100',
            'contacto' => 'Pedro Sánchez',
        ]);

        Proveedor::create([
            'nombre' => 'Importaciones XYZ',
            'ruc' => '20987654321',
            'email' => 'ventas@xyz.com',
            'telefono' => '(01) 5552222',
            'direccion' => 'Jr. Comercio 200',
            'contacto' => 'Ana García',
        ]);
    }
}
