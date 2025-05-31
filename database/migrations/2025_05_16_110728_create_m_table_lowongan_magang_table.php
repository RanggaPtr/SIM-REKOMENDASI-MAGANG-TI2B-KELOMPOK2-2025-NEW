<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::create('m_lowongan_magang', function (Blueprint $table) {
            $table->bigIncrements('lowongan_id');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('skema_id');
            $table->string('judul', 255);
            $table->text('deskripsi');
            $table->text('persyaratan');
            $table->string('bidang_keahlian', 255);  // tambahkan iniAdd commentMore actions
            $table->decimal('minimal_ipk', 3, 2)->nullable();  // tambahkan ini
            $table->integer('tunjangan');
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('m_perusahaan')->onDelete('cascade');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode_magang')->onDelete('cascade');
            $table->foreign('skema_id')->references('skema_id')->on('m_skema')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_lowongan_magang');
    }
};
