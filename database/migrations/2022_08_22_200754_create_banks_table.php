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
        Schema::create('banks', function (Blueprint $table) {
            $table->id('id_bank');
            $table->string('name_bank');
            $table->string('description_bank')->nullable();
            $table->string('account_number_bank');
            $table->integer('id_sub_ledger_account')->nullable();
            $table->integer('id_company')->nullable();
            $table->boolean('enabled_bank')->default(1);
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
        Schema::dropIfExists('banks');
    }
};
