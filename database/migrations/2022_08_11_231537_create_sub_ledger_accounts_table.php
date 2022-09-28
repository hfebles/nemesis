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
        Schema::create('sub_ledger_accounts', function (Blueprint $table) {
            $table->id('id_sub_ledger_account');
            $table->integer('id_ledger_account');
            $table->string('code_sub_ledger_account');
            $table->string('name_sub_ledger_account');
            $table->integer('id_type_ledger_account')->nullable();
            $table->boolean('enabled_sub_ledger_account')->default(1);
            
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
        Schema::dropIfExists('sub_ledger_accounts');
    }
};
