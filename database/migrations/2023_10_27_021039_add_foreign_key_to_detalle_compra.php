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
        Schema::table('detalle_compra', function (Blueprint $table) {
            Schema::table('detalle_compras', function (Blueprint $table) {
                $table->unsignedBigInteger('detalle_producto_id');
                $table->foreign('detalle_producto_id')
                      ->references('id')->on('detalle_productos');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_compra', function (Blueprint $table) {
            //
        });
    }
};
