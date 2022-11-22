<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('id_purchase_order');
            $table->string('ref_name_purchase_order')->nullable(); // numero de factura o referencia de factura
            $table->string('ctrl_num_purchase_order')->nullable();
            $table->integer('ctrl_num')->nullable();
            $table->float('total_amount_purchase_order', 8, 2)->nullable();
            $table->float('exempt_amout_purchase_order', 8, 2)->nullable();
            $table->float('no_exempt_amout_purchase_order', 8, 2)->nullable();
            $table->float('total_amount_tax_purchase_order', 8, 2)->nullable();
            $table->float('residual_amount_purchase_order', 8, 2)->nullable();
            $table->date('date_purchase_order')->nullable();
            $table->integer('type_payment')->nullable();
            $table->integer('id_exchange')->nullable();
            $table->integer('id_order_state')->default(1);
            $table->integer('id_company')->nullable();
            $table->integer('id_supplier')->nullable();
            $table->integer('id_user')->nullable();
            $table->integer('id_worker')->nullable();
            $table->integer('id_purchase')->nullable();
            $table->boolean('enabled_purchase_order')->default(1);



            /* Ajustes de tabla */
            $table->string('ref_supplier_order')->nullable();
            
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
        Schema::dropIfExists('purchase_orders');
    }
}
