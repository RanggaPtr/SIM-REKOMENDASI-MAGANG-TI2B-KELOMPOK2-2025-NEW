<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableLowonganMagangTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_lowongan_magang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('skema_id')->nullable();
            $table->string('judul', 255);
            $table->text('deskripsi');
            $table->text('persyaratan');
            $table->string('bidang_keahlian', 255);
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('id')->on('m_table_perusahaan')->onDelete('restrict');
            $table->foreign('periode_id')->references('id')->on('m_table_periode_magang')->onDelete('restrict');
            $table->foreign('skema_id')->references('id')->on('m_table_skema')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_lowongan_magang');
    }
};
