<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailSemesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_semester', function (Blueprint $table) {
            $table->foreignId('studyprogram_id')->references('id')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('semester_id')->references('id')->on('semester')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('detail_semester');
    }
}
