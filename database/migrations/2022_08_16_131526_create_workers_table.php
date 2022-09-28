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
        Schema::create('workers', function (Blueprint $table) {
            $table->id('id_worker');
            $table->string('firts_name_worker');
            $table->string('last_name_worker');
            $table->string('dni_worker');
            $table->string('phone_worker')->nullable();
            $table->string('mail_worker')->nullable();
            $table->integer('id_group_worker');
            $table->integer('id_user')->nullable();
            $table->integer('id_company')->nullable();
            $table->boolean('enabled_worker')->default(1);
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
        Schema::dropIfExists('workers');
    }
};
