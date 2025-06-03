<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_dosen', function (Blueprint $table) {
            $table->bigIncrements('dosen_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nik', 20);
            $table->unsignedBigInteger('prodi_id');
            $table->unsignedBigInteger('kompetensi_id')->nullable();
            $table->integer('jumlah_bimbingan')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
            $table->foreign('prodi_id')->references('prodi_id')->on('m_program_studi')->onDelete('cascade');
            $table->foreign('kompetensi_id')->references('kompetensi_id')->on('m_kompetensi')->onDelete('set null'); // Foreign key
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_dosen');
    }
};
