<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTableEvaluasiMagangTable extends Migration
{
    public function up()
    {
        Schema::create('t_evaluasi_magang', function (Blueprint $table) {
            $table->bigIncrements('evaluasi_magang_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->integer('nilai');
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('t_pengajuan_magang')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_evaluasi_magang');
    }
};
