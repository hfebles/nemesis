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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product');
            $table->string('name_product');
            $table->string('description_product')->nullable();
            $table->float('price_product', 8, 2);
            $table->boolean('product_usd_product')->default(0);
            $table->boolean('tax_exempt_product')->default(0);
            $table->float('qty_product')->nullable();
            $table->integer('salable_product')->default(0);
            $table->string('code_product')->nullable();
            $table->string('part_number_product')->nullable();
            $table->string('lot_number_product')->nullable();
            $table->integer('id_warehouse')->nullable();
            $table->integer('id_product_category')->nullable();
            $table->integer('id_unit_product')->nullable();
            $table->integer('id_presentation_product')->nullable();
            $table->boolean('enabled_product')->default(1);


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
        Schema::dropIfExists('products');
    }
};
