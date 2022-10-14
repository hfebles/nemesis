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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payment');
            $table->integer('id_client')->nullable();
            $table->integer('id_invoice')->nullable();
            $table->integer('id_delivery_note')->nullable();
            $table->integer('type_pay')->nullable();
            $table->float('amount_payment', 8, 2);
            $table->string('ref_payment');
            $table->date('date_payment');
            $table->integer('id_bank')->nullable();
            $table->boolean('enabled_payment')->default(1);
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
        Schema::dropIfExists('payments');
    }
};
