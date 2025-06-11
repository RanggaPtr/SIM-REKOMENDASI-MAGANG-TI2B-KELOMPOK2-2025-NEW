<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('nama', 255)->nullable(false);
            $table->string('username', 255)->unique()->nullable(false);
            $table->string('email', 255)->unique()->nullable(false);
            $table->string('password', 255)->nullable(false);
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->nullable(false);
            $table->string('foto_profile', 255)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_users');
    }
};
