<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('m_users')->onDelete('cascade');

            // Profil akademik & keterampilan
            $table->string('academic_profile')->nullable();
            $table->text('skills')->nullable();
            $table->text('certifications')->nullable();
            $table->text('experiences')->nullable();

            // Preferensi magang
            $table->string('preferred_location')->nullable();
            $table->string('internship_type')->nullable();

            // Dokumen upload
            $table->string('cv_path')->nullable();
            $table->string('cover_letter_path')->nullable();
            $table->string('certificate_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
