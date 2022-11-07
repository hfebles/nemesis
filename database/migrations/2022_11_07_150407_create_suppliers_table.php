<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('name_supplier');
            $table->string('idcard_supplier');
            $table->text('address_supplier');
            $table->string('phone_supplier')->nullable();
            $table->string('email_supplier')->nullable();
            $table->integer('zip_supplier')->nullable();
            $table->integer('id_company')->nullable();
            $table->integer('id_state');
            $table->boolean('taxpayer_supplier')->default(0);
            $table->integer('porcentual_amount_tax_supplier')->nullable();
            $table->boolean('enabled_supplier')->default(1);
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
        Schema::dropIfExists('suppliers');
    }
}
