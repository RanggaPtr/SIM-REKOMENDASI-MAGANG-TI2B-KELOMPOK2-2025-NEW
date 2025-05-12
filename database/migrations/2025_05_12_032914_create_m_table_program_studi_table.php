<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTableProgramStudiTable extends Migration
{
    public function up()
    {
        Schema::create('m_table_program_studi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_table_program_studi');
    }
};
