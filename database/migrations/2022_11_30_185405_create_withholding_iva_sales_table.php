<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithholdingIvaSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholding_iva_sales', function (Blueprint $table) {
            $table->id('id_withholding_iva_sale');
            $table->bigInteger('voucher_number_whs')->nullable();
            $table->tinyInteger('type_wh')->default(0)->comment('0. factura cliente, 1. Factura proveedor');
            $table->date('date_whs');
            $table->boolean('state_wh')->default(1)->comment('0. confirmado, 1. retenido, 2. declarado');
            $table->float('amount_tax_invoice_whs', 8, 2)->comment('monto total del impuesto en la factura');
            $table->float('amount_base_imponible_whs', 8, 2)->comment('monto de la base imponible');
            $table->float('amount_tax_retention_whs', 8, 2)->comment('monto retenido');
            // $table->unsignedBigInteger('id_tax');
            // $table->foreign('id_tax')->references('id_tax')->on('taxes');
            $table->unsignedBigInteger('id_client');
            $table->foreign('id_client')->references('id_client')->on('clients');
            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id_invoicing')->on('invoicings');
            $table->string('enabled_whs')->default(1);
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
        Schema::dropIfExists('withholding_iva_sales');
    }
}
