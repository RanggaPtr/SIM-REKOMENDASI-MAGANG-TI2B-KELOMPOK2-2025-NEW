<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedback_log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('feedback_log_aktivitas_id');

            $table->unsignedBigInteger('log_aktivitas_id');
            $table->unsignedBigInteger('dosen_id');
            $table->text('komentar');
            $table->timestamps();

            $table->foreign('log_aktivitas_id')->references('log_id')->on('t_log_aktivitas')->onDelete('cascade');
            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback_log_aktivitas');
    }
};
