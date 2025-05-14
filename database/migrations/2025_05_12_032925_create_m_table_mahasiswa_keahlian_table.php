<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableMahasiswaKeahlianTable extends Migration
{
    public function up()
    {
        Schema::create('m_mahasiswa_keahlian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('keahlian_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('m_mahasiswa')->onDelete('restrict');
            $table->foreign('keahlian_id')->references('id')->on('m_keahlian')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_mahasiswa_keahlian');
    }
}

