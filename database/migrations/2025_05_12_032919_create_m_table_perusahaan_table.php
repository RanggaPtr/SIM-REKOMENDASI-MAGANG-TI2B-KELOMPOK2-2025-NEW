<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTablePerusahaanTable extends Migration
{
    public function up()
    {
        Schema::create('m_perusahaan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 255);
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->string('kontak', 255);
            $table->string('bidang_industri', 255);
            $table->timestamps();

            $table->foreign('lokasi_id')->references('id')->on('m_lokasi')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_perusahaan');
    }
};
