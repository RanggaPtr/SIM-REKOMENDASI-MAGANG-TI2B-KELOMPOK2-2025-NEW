<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('m_wilayah', function (Blueprint $table) {
            $table->bigIncrements('wilayah_id');
            $table->string('nama', 255);
            $table->string('kode_wilayah', 20)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_wilayah');
    }
};
