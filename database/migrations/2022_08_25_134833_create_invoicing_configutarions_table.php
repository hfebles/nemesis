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
        Schema::create('invoicing_configutarions', function (Blueprint $table) {
            $table->id('id_invoicing_configutarion');
            $table->string('print_name_invoicing_configutarion')->nullable();
            $table->string('correlative_invoicing_configutarion')->nullable();
            $table->integer('control_number_invoicing_configutarion')->nullable();
            $table->unsignedBigInteger('id_ledger_account');
            $table->foreign('id_ledger_account')->references('id_ledger_account')->on('ledger_accounts');            
            $table->boolean('enabled_invoicing_configutarion')->default(1);
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
        Schema::dropIfExists('invoicing_configutarions');
    }
};
