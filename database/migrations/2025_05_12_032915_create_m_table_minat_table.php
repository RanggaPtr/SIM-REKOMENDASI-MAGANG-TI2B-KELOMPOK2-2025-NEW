<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableMinatTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_minat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_minat');
    }
};
