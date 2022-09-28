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
        Schema::create('record_accoutings', function (Blueprint $table) {
            $table->id('id_record_accouting');
            $table->string('reference_record_accouting');
            $table->string('description_record_accouting');
            $table->boolean('enabled_record_accouting')->default(1);
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
        Schema::dropIfExists('record_accoutings');
    }
};
