<?php

use FontLib\Table\Type\name;
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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id('id_tax');
            $table->string('name_tax');
            $table->float('amount_tax', 8, 2)->nullable();
            $table->integer('billable_tax')->default(0);
            $table->integer('id_sub_ledger_account')->nullable();
            $table->integer('id_company')->nullable();
            $table->boolean('enabled_tax')->default(1);
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
        Schema::dropIfExists('taxes');
    }
};
