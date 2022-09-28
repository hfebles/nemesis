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
            $table->string('control_number_invoicing_configutarion')->nullable();
            $table->integer('id_sub_ledger_account')->nullable();
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
