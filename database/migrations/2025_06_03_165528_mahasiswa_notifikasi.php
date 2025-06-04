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
        Schema::create('t_mhs_notifikasi', function (Blueprint $table) {
            $table->bigIncrements('mhs_notifikasi_id');
            $table->unsignedBigInteger('mahasiswa_id');

            $table->string('status')->default('ditolak');
            $table->text('deskripsi');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mhs_notifikasi');
    }
};
