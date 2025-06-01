<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_pengajuan_magang', function (Blueprint $table) {
            $table->bigIncrements('pengajuan_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->decimal('feedback_rating', 2, 1)->nullable(); // Allows values like 4.5, 3.0, etc.Add commentMore actions
            $table->string('feedback_deskripsi')->nullable();
            $table->string('status', 50);
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_pengajuan_magang');
    }
};
