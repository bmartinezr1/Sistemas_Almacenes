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
        Schema::create('ventas', function (Blueprint $table){
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->enum('medio_pago', ['Efectivo', 'Tarjeta', 'Transferencia', 'Credito']);
            $table->integer('monto_recibido')->nullable();
            $table->enum('estado', ['Completada', 'Pendiente'])->default ('Completada');
            $table->string('motivo_anulacion')->nullable();
            $table->foreignId('usuario_anulacion_id')->nullable()->constrained('users');
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
