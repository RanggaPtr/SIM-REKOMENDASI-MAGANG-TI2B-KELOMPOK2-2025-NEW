<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableDosenTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_dosen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('nidn', 20)->unique();
            $table->unsignedBigInteger('program_studi_id')->nullable();
            $table->integer('jumlah_bimbingan')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('m_table_users')->onDelete('restrict');
            $table->foreign('program_studi_id')->references('id')->on('m_table_program_studi')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_dosen');
    }
};
