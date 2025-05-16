<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_admin', function (Blueprint $table) {
            $table->bigIncrements('admin_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nik', 20);
            $table->string('jabatan', 100);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_admin');
    }
};
