<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMbkmGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('involved_course', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reg_mbkm_id');
            $table->foreign('reg_mbkm_id')->references('id')->on('reg_mbkms')->onDelete('cascade');
            $table->string('kode_matkul', 20);
            $table->string('nama_matkul');
            $table->integer('sks');
            $table->integer('grade')->nullable();
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
        Schema::dropIfExists('mbkm_grades');
    }
}
