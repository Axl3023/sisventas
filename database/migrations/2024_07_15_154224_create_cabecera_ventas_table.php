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
        Schema::create('cabecera_ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->datetime('fecha_venta');
            $table->unsignedBigInteger('id_tipo');
            $table->string('nro_doc')->nullable();
            $table->float('total');
            $table->float('subtotal');
            $table->float('igv');
            $table->boolean('estado');
            // FK
            $table->foreign('id_cliente')->references('id')->on('clientes');
            $table->foreign('id_tipo')->references('id')->on('tipo_documentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabecera_ventas');
    }
};
