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
       Schema::create('producto', function (Blueprint $table){
            $table->id();
            $table->string('sku')->unique();
            $table->string('codigo_barras')->unique()->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->integer('precio_venta')->default(0);
            $table->string('unidad');
            $table->integer('stock_minimo')->default(0);
            $table->integer('cantidad_actual')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
       }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
