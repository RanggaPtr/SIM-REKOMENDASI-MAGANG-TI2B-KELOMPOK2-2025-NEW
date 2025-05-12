<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('nim', 20)->unique();
            $table->unsignedBigInteger('program_studi_id');
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->unsignedBigInteger('minat_id')->nullable();
            $table->unsignedBigInteger('skema_id')->nullable();
            $table->decimal('ipk', 4, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('m_table_users')->onDelete('restrict');
            $table->foreign('program_studi_id')->references('id')->on('m_table_program_studi')->onDelete('restrict');
            $table->foreign('lokasi_id')->references('id')->on('m_table_lokasi')->onDelete('restrict');
            $table->foreign('minat_id')->references('id')->on('m_table_minat')->onDelete('restrict');
            $table->foreign('skema_id')->references('id')->on('m_table_skema')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_mahasiswa');
    }
};
