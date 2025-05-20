<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m_users', function (Blueprint $table) {
            if (!Schema::hasColumn('m_users', 'foto_profile')) {
                $table->string('foto_profile')->nullable();
            }

            if (!Schema::hasColumn('m_users', 'no_telepon')) {
                $table->string('no_telepon')->nullable();
            }

            if (!Schema::hasColumn('m_users', 'alamat')) {
                $table->text('alamat')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('m_users', function (Blueprint $table) {
            if (Schema::hasColumn('m_users', 'foto_profile')) {
                $table->dropColumn('foto_profile');
            }

            if (Schema::hasColumn('m_users', 'no_telepon')) {
                $table->dropColumn('no_telepon');
            }

            if (Schema::hasColumn('m_users', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
