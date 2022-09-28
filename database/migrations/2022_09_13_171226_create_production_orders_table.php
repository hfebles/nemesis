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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id('id_production_order');
            $table->integer('number_production_order')->nullable();
            $table->string('name_production_order', 100);
            $table->date('date_from_production_order');
            $table->date('date_to_production_order');
            $table->integer('planned_qty_production_order');
            $table->integer('id_product');
            $table->integer('id_material_list');
            $table->integer('id_user');
            $table->integer('id_production_order_state')->default(1); // 1: A producir, 2: Producido, 3: Finalizado
            $table->boolean('enabled_production_order')->default(1);
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
        Schema::dropIfExists('production_orders');
    }
};
