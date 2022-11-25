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
        Schema::create('moves_accounts', function (Blueprint $table) {
            $table->id('id_moves_account');
            $table->integer('id_invocing')->nullable();
            $table->integer('id_purchase')->nullable();
            $table->date('date_moves_account');
            $table->boolean('type_moves_account')->default(0);
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
        Schema::dropIfExists('moves_accounts');
    }
};
