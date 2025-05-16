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
            $table->unsignedBigInteger('user_id');
            $table->text('aktivitas');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_log_aktivitas');
    }
};
