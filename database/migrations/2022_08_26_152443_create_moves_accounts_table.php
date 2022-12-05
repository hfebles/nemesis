<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moves_accounts', function (Blueprint $table) {
            $table->id('id_moves_account');
            $table->integer('id_invocing')->nullable();
            $table->integer('id_purchase')->nullable();
            $table->date('date_moves_account');
            $table->tinyInteger('type_moves_account')->comment('1. Venta, 2. Compra, 3. Pago de Facturas Ventas, 4. Pago Facturas compras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moves_accounts');
    }
};
