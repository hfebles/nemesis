<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('id_delivery');
            $table->string('guide_delivery');
            $table->date('date_delivery');
            $table->text('ids_invoices');
            $table->integer('id_zone');
            $table->text('comment_delivery')->nullable();
            $table->integer('state_delivery')->default(0);
            $table->integer('id_worker'); //chofer
            $table->integer('id_caletero');
            $table->boolean('enabled_delivery')->default(1);
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
        Schema::dropIfExists('deliveries');
    }
}
