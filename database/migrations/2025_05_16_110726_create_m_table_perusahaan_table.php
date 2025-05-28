<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_perusahaan', function (Blueprint $table) {
            $table->bigIncrements('perusahaan_id');
            $table->string('nama', 255);
            $table->text('ringkasan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('logo', 255)->nullable();
            $table->text('alamat');
            $table->unsignedBigInteger('wilayah_id');
            $table->string('kontak', 50);
            $table->string('bidang_industri', 100);
            $table->decimal('rating', 3, 1)->nullable();
            $table->text('deskripsi_rating')->nullable();
            $table->timestamps();

            $table->foreign('wilayah_id')->references('wilayah_id')->on('m_wilayah')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_perusahaan');
    }
};
