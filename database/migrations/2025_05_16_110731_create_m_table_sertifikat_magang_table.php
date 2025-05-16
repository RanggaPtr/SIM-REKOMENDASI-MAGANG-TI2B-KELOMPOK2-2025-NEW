<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('m_sertifikat_magang', function (Blueprint $table) {
            $table->bigIncrements('sertifikat_magang_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('nama_dokumen', 255);
            $table->string('jenis_dokumen', 100);
            $table->string('file_dokumen', 255); // Disimpan sebagai path
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('t_pengajuan_magang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_sertifikat_magang');
    }
};
