<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('m_kompetensi', function (Blueprint $table) {
            $table->bigIncrements('kompetensi_id');
            $table->string('nama', 255);
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('program_studi_id');
            $table->timestamps();

            $table->foreign('program_studi_id')->references('prodi_id')->on('m_program_studi')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('m_kompetensi');
    }
};
