<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableSkemaTable extends Migration
{
    public function up()
    {
        Schema::create('m_skema', function (Blueprint $table) {
            $table->bigIncrements('skema_id');
            $table->string('nama', 255);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_skema');
    }
};
