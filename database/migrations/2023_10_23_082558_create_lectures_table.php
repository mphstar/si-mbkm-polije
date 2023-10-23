<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->string("nip")->unique()->primary();
            $table->string("nama_dosen",50);
            $table->string("email");
            $table->string("alamat");
            $table->string("no_telfon",16);
            $table->string("username");
            $table->string("password");
            $table->enum('status', ['admin prodi', 'dosen pembimbing','kaprodi']);
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
        Schema::dropIfExists('lectures');
    }
}
