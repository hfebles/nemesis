<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_configs', function (Blueprint $table) {
            $table->id('id_purchase_order_config');
            $table->string('print_name_purchase_order_config')->nullable();
            $table->string('correlative_purchase_order_config')->nullable();
            $table->integer('control_number_purchase_order_config')->nullable();
            $table->boolean('enabled_purchase_order_config')->default(1);
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
        Schema::dropIfExists('purchase_order_configs');
    }
}
