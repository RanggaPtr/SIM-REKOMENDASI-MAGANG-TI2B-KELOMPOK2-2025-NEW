<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTablePengajuanMagangTable extends Migration
{
    public function up()
    {
        Schema::create('t_table_pengajuan_magang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->unsignedBigInteger('periode_id');
            $table->enum('status', ['diajukan', 'ditolak', 'diterima', 'ongoing', 'selesai'])->default('diajukan');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('m_table_mahasiswa')->onDelete('restrict');
            $table->foreign('lowongan_id')->references('id')->on('m_table_lowongan_magang')->onDelete('restrict');
            $table->foreign('dosen_id')->references('id')->on('m_table_dosen')->onDelete('restrict');
            $table->foreign('periode_id')->references('id')->on('m_table_periode_magang')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_table_pengajuan_magang');
    }
};
