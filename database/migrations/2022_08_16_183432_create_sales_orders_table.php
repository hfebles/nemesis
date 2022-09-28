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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id('id_sales_order');
            $table->string('ref_name_sales_order')->nullable(); // numero de factura o referencia de factura
            $table->string('ctrl_num_sales_order')->nullable();
            $table->integer('ctrl_num')->nullable();
            $table->float('total_amount_sales_order', 8, 2)->nullable();
            $table->float('exempt_amout_sales_order', 8, 2)->nullable();
            $table->float('no_exempt_amout_sales_order', 8, 2)->nullable();
            $table->float('total_amount_tax_sales_order', 8, 2)->nullable();
            $table->float('residual_amount_sales_order', 8, 2)->nullable();
            $table->date('date_sales_order')->nullable();
            $table->integer('type_payment')->nullable();
            $table->integer('id_exchange')->nullable();
            $table->integer('id_order_state')->default(1);
            $table->integer('id_company')->nullable();
            $table->integer('id_client')->nullable();
            $table->integer('id_user')->nullable();
            $table->integer('id_worker')->nullable();
            $table->integer('id_invoice')->nullable();
            $table->boolean('enabled_sales_order')->default(1);
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
        Schema::dropIfExists('sales_orders');
    }
};
