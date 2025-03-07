<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMbkmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mbkms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->string('jurusan');
            $table->string('program_name');
            $table->integer('capacity');
            $table->date('start_date');
            $table->date('start_reg');
            $table->date('end_reg');
            $table->date('end_date');
            $table->integer('task_count');
            $table->integer('semester');
            $table->string('nama_penanggung_jawab');
            $table->integer('jumlah_sks');
          
            $table->text('info')->nullable();
            $table->enum('status_acc', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->enum('is_active', ['active', 'inactive'])->default('inactive');

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
        Schema::dropIfExists('mbkms');
    }
}
