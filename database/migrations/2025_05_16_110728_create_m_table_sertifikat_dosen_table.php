<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('m_sertifikat_dosen', function (Blueprint $table) {
            $table->bigIncrements('sertifikat_dosen_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('nama_sertifikat', 255);
            $table->string('penerbit', 255);
            $table->date('tanggal_terbit');
            $table->string('file_sertifikat', 255); // Disimpan sebagai path
            $table->timestamps();

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_sertifikat_dosen');
    }   
};
