<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableLowonganKeahlianTable extends Migration
{
    public function up()
    {
        Schema::create('m_lowongan_keahlian', function (Blueprint $table) {
            $table->bigIncrements('lowongan_keahlian_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('keahlian_id');
            $table->timestamps();

            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('restrict');
            $table->foreign('keahlian_id')->references('keahlian_id')->on('m_keahlian')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_lowongan_keahlian');
    }
}