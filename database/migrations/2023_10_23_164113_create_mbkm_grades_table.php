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
        Schema::create('mbkm_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reg_mbkm_id');
            $table->foreign('reg_mbkm_id')->references('id')->on('reg_mbkms')->onDelete('cascade');
            $table->integer('grade');
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
