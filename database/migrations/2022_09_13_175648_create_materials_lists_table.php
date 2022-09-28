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
        Schema::create('materials_lists', function (Blueprint $table) {
            $table->id('id_materials_list');
            $table->integer('id_product');
            $table->string('name_materials_list', 100);
            $table->integer('qty_materials_list');
            $table->integer('id_presentation');
            $table->boolean('enabled_materials_list')->default(1);
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
        Schema::dropIfExists('materials_lists');
    }
};
