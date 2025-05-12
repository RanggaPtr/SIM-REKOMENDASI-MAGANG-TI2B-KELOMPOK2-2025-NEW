<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTableLogAktivitasTable extends Migration
{
    public function up()
    {
        Schema::create('t_table_log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('aktivitas');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('m_table_users')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_table_log_aktivitas');
    }
};
