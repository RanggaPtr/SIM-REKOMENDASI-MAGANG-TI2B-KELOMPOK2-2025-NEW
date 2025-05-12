<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTablePeriodeMagangTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_periode_magang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 255);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_periode_magang');
    }};
