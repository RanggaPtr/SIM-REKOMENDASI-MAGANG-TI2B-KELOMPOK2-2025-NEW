<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableSertifikatMagangTable extends Migration
{
    public function up()
    {
        Schema::create('m_sertifikat_magang', function (Blueprint $table) {
            $table->bigIncrements('sertifikat_magang_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('nama_dokumen', 255);
            $table->enum('jenis_dokumen', ['sertifikat', 'surat_keterangan']);
            $table->string('file_dokumen', 255);
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('t_pengajuan_magang')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_sertifikat_magang');
    }
};
