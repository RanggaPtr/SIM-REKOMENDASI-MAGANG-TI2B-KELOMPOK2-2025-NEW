<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('m_mahasiswa_kompetensi', function (Blueprint $table) {
            $table->bigIncrements('mahasiswa_kompetensi_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('kompetensi_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('kompetensi_id')->references('kompetensi_id')->on('m_kompetensi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_mahasiswa_kompetensi');
    }
};
