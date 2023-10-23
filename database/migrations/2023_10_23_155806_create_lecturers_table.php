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
            $table->string("email")->unique();
            $table->string("username")->unique();
            $table->string("password");
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
