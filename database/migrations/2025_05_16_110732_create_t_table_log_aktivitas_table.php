<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('t_log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->text('aktivitas');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('t_pengajuan_magang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_log_aktivitas');
    }
};
