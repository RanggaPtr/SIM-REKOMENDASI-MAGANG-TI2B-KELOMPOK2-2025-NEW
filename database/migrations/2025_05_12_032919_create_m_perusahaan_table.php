<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTablePerusahaanTable extends Migration
{
    public function up()
    {
        Schema::create('m_perusahaan', function (Blueprint $table) {
            $table->bigIncrements('perusahaan_id');
            $table->string('nama', 255);
            $table->string('ringkasan', 255);
            $table->text('deskripsi');
            $table->string('logo', 255);
            $table->string('alamat', 255);
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->string('kontak', 255);
            $table->string('bidang_industri', 255);
            // rating by admin
            $table->float('rating')->default(0);
            // deskripsi rating
            $table->text('deskripsi_rating')->nullable();
            
            $table->timestamps();

            $table->foreign('wilayah_id')->references('lokasi_id')->on('m_lokasi')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_perusahaan');
    }
};
