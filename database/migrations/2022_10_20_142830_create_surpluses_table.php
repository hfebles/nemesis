<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurplusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surpluses', function (Blueprint $table) {
            $table->id('id_surplus');
            $table->float('amount_surplus', 8, 2);
            $table->unsignedBigInteger('id_payment');
            $table->foreign('id_payment')->references('id_payment')->on('payments');
            $table->unsignedBigInteger('id_client');
            $table->foreign('id_client')->references('id_client')->on('clients');

            $table->integer('id_payment_used')->nullable();
            $table->boolean('used_surplus')->default(1);
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
        Schema::dropIfExists('surpluses');
    }
}
