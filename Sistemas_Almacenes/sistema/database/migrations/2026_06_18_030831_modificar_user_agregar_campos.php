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
        Schema::table('users', function (Blueprint $table){
            $table->string('nombre')->after('id');
            $table->string('apellido')->after('nombre');
            $table->string('rut')->unique()->after('apellido');
            $table->enum('cargo', ['Admin','Vendedor'])->after('rut');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users',function (Blueprint $table){
            $table->string('name')->after ('id');
            $table->dropColumn(['nombre', 'apellido', 'rut', 'cargo']);
        });
    }
};
