<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template', function (Blueprint $table) {
            $table->id();
            $table->string('nama',50);
            $table->string('file',255);
            $table->foreignId('id_jenis_document')->references('id')->on('jenis_document')->onUpdate('cascade')->onDelete('cascade');
            // $table->integer('id_jenis_document'); // Use unsignedInteger instead of unsignedBigInteger

            // $table->foreign('id_jenis_document')
            //     ->references('id')
            //     ->on('jenis_document'); // Assuming you want cascade on delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template');
    }
}
