<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string("lecturer_name");
            $table->string("address");
            $table->string("phone");
            $table->string("nip")->unique();
            $table->foreignId('study_program_id')->nullable()->references('id')->on('study_programs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('users_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['dosen pembimbing', 'admin prodi', 'kaprodi']);
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
        Schema::dropIfExists('lecturers');
    }
}
