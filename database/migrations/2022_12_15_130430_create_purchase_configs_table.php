<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_configs', function (Blueprint $table) {
            $table->id('id_purchase_config');
            $table->string('print_name_id_purchase_config')->nullable();
            $table->string('correlative_id_purchase_config')->nullable();
            $table->integer('control_number_id_purchase_config')->nullable();
            $table->unsignedBigInteger('id_ledger_account');
            $table->foreign('id_ledger_account')->references('id_ledger_account')->on('ledger_accounts');
            $table->boolean('enabled_id_purchase_config')->default(1);
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
        Schema::dropIfExists('purchase_configs');
    }
}
