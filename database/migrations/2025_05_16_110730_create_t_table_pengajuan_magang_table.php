<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_pengajuan_magang', function (Blueprint $table) {
            $table->bigIncrements('pengajuan_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('periode_id');
            $table->string('status', 50);
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('cascade');
            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen')->onDelete('cascade');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode_magang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_pengajuan_magang');
    }
};
