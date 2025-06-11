<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('mahasiswa_id');
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('nim', 20)->unique()->nullable(false);
            $table->unsignedBigInteger('program_studi_id')->nullable(); // Izinkan null jika belum ada data
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->unsignedBigInteger('periode_id')->nullable();
            $table->unsignedBigInteger('skema_id')->nullable();
            $table->string('file_cv', 255)->nullable();
            $table->decimal('ipk', 3, 2)->nullable(); // Izinkan null jika belum ada data
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
            $table->foreign('program_studi_id')->references('prodi_id')->on('m_program_studi')->onDelete('set null');
            $table->foreign('wilayah_id')->references('wilayah_id')->on('m_wilayah')->onDelete('set null');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode_magang')->onDelete('set null');
            $table->foreign('skema_id')->references('skema_id')->on('m_skema')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_mahasiswa');
    }
};