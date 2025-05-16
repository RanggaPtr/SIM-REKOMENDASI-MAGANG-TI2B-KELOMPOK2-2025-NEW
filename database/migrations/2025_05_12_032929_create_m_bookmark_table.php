<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableBookmarkTable extends Migration
{
    public function up()
    {
        Schema::create('m_bookmark', function (Blueprint $table) {
            $table->bigIncrements('bookmark_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('restrict');
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_bookmark');
    }
};
