<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('m_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('mahasiswa_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nim', 20)->unique();
            $table->unsignedBigInteger('program_studi_id');
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->unsignedBigInteger('skema_id')->nullable();
            $table->decimal('ipk', 4, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('restrict');
            $table->foreign('program_studi_id')->references('prodi_id')->on('m_program_studi')->onDelete('restrict');
            $table->foreign('wilayah_id')->references('wilayah_id')->on('m_wilayah')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_mahasiswa');
    }
};
