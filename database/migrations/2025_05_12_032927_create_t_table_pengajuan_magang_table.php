<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTablePengajuanMagangTable extends Migration
{
    public function up()
    {
        Schema::create('t_pengajuan_magang', function (Blueprint $table) {
            $table->bigIncrements('pengajuan_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->unsignedBigInteger('periode_id');
            $table->enum('status', ['belum_akses', 'diajukan', 'ditolak', 'diterima', 'ongoing', 'selesai'])->default('belum_akses');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('restrict');
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('restrict');
            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen')->onDelete('restrict');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode_magang')->onDelete('restrict');
        });
    }

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Reverse the migrations.
     *
     * This method drops the 't_pengajuan_magang' table
     * if it exists, effectively rolling back the migration.
     */

/*******  8accfa3c-ad14-4213-967a-c96f7e63a7fd  *******/    public function down()
    {
        Schema::dropIfExists('t_pengajuan_magang');
    }
}