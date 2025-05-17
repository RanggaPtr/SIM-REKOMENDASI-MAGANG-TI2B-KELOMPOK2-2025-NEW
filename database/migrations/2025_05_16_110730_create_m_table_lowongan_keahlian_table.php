<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_lowongan_keahlian', function (Blueprint $table) {
            $table->bigIncrements('lowongan_keahlian_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('keahlian_id');
            $table->timestamps();

            // Tambahkan foreign key constraints
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_lowongan_magang')->onDelete('cascade');
            $table->foreign('keahlian_id')->references('keahlian_id')->on('m_keahlian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_lowongan_keahlian');
    }
};
