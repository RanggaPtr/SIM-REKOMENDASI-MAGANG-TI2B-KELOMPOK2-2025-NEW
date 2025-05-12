<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableMahasiswaKompetensiTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_mahasiswa_kompetensi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('kompetensi_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('m_table_mahasiswa')->onDelete('restrict');
            $table->foreign('kompetensi_id')->references('id')->on('m_table_kompetensi')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_mahasiswa_kompetensi');
    }
};
