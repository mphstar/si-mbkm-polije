<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string("partner_name");
            $table->string("address");
            $table->string("phone");
            $table->string("email")->unique();
            $table->enum('status', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->enum('jenis_mitra', ['luar kampus', 'dalam kampus'])->default('dalam kampus');
            $table->string("username")->unique();
            $table->string("password");
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
        Schema::dropIfExists('partners');
    }
}
