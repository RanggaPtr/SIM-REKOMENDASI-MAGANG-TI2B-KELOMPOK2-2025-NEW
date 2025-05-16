<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableDosenTable extends Migration
{
    public function up()
    {
        Schema::create('m_dosen', function (Blueprint $table) {
            $table->bigIncrements('dosen_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nik', 20)->unique();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->integer('jumlah_bimbingan')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('restrict');
            $table->foreign('prodi_id')->references('prodi_id')->on('m_program_studi')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_dosen');
    }
};
