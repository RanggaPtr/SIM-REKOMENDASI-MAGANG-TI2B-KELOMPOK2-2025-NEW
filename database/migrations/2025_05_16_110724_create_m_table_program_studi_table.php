<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('m_program_studi', function (Blueprint $table) {
            $table->bigIncrements('prodi_id');
            $table->string('nama', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_program_studi');
    }
};
