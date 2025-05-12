<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableAdminTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_admin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('nik', 20)->nullable()->unique();
            $table->string('jabatan', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('m_table_users')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_admin');
    }
};
