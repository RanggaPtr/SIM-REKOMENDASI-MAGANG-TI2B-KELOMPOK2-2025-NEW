<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableSertifikatMagangTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_sertifikat_magang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('nama_dokumen', 255);
            $table->enum('jenis_dokumen', ['sertifikat', 'surat_keterangan']);
            $table->binary('file_dokumen');
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('t_table_pengajuan_magang')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_sertifikat_magang');
    }
};
