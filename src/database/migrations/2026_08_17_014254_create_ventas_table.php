<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->decimal('monto_entregado', 10, 2)->nullable();
            $table->decimal('vuelto', 10, 2)->nullable();
            $table->enum('estado', ['completada', 'anulada', 'pendiente'])->default('completada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
