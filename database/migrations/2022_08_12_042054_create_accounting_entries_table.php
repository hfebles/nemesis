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
        Schema::create('accounting_entries', function (Blueprint $table) {
            $table->id('id_accounting_entries');
            $table->integer('id_ledger_account');
            $table->integer('id_moves_account');
            $table->string('description_accounting_entries');
            $table->string('date_accounting_entries');
            $table->float('amount_debe_accounting_entries', 8, 2)->nullable();
            $table->float('amount_haber_accounting_entries', 8, 2)->nullable();
            $table->boolean('enabled_accounting_entries')->default(1);
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
        Schema::dropIfExists('accounting_entries');
    }
};
