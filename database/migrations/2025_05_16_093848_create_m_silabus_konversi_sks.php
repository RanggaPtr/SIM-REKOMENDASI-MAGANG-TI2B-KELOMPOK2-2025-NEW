<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableSilabusKonversiSksTable extends Migration
{
    public function up()
    {
        Schema::create('m_silabus_konversi_sks', function (Blueprint $table) {
            $table->bigIncrements('silabus_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->integer('jumlah_sks');
            $table->text('deskripsi');
            $table->text('kriteria')->nullable();
            $table->string('dokumen_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_silabus_konversi_sks');
    }
}