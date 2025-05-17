<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_lowongan_kompetensi', function (Blueprint $table) {
            $table->bigIncrements('lowongan_kompetensi_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('kompetensi_id');
            $table->timestamps();

            // Tambahkan foreign key constraints
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('cascade');
            $table->foreign('kompetensi_id')->references('kompetensi_id')->on('m_kompetensi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_lowongan_kompetensi');
    }
};
