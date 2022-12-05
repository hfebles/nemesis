<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithholdingIvaPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholding_iva_purchases', function (Blueprint $table) {
            $table->id('id_withholding_iva_purchase');
            $table->bigInteger('voucher_number_whp');
            $table->integer('voucher_control_whp');
            $table->tinyInteger('type_wh')->default(0)->comment('0. factura cliente, 1. Factura proveedor');
            $table->date('date_whp');
            $table->boolean('state_wh')->default(0)->comment('0. confirmado, 1. retenido, 2. declarado');
            $table->float('amount_tax_invoice_whp', 8, 2)->comment('monto total del impuesto');
            $table->float('amount_base_imponible_whp', 8, 2)->comment('monto de la base imponible');
            $table->float('amount_tax_retention_whp', 8, 2)->comment('monto retenido');
            //$table->unsignedBigInteger('id_tax');
            //$table->foreign('id_tax')->references('id_tax')->on('taxes');
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers');
            $table->unsignedBigInteger('id_purchase');
            $table->foreign('id_purchase')->references('id_purchase')->on('purchases');
            $table->string('enabled_whp')->default(1);
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
        Schema::dropIfExists('withholding_iva_purchases');
    }
}
