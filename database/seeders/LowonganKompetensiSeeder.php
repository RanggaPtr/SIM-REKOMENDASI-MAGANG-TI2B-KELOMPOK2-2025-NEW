<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LowonganMagangModel;
use App\Models\KompetensiModel;

class LowonganKompetensiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['lowongan_id' => 1, 'kompetensi_id' => 1], // Web Developer - Web Development
            ['lowongan_id' => 2, 'kompetensi_id' => 10], // Data Analyst - Business Intelligence
            ['lowongan_id' => 2, 'kompetensi_id' => 6], // Data Analyst - Data Science
            ['lowongan_id' => 3, 'kompetensi_id' => 17], // Content Creator - Digital Content Creation
            ['lowongan_id' => 4, 'kompetensi_id' => 13], // Mobile Developer - Mobile App Development
            ['lowongan_id' => 5, 'kompetensi_id' => 12], // UI/UX Designer - UI/UX Design
            ['lowongan_id' => 6, 'kompetensi_id' => 2], // Backend Developer - Back End Development
            ['lowongan_id' => 7, 'kompetensi_id' => 11], // Front End Developer - Front End Development
            ['lowongan_id' => 8, 'kompetensi_id' => 14], // DevOps Engineer - DevOps
            ['lowongan_id' => 9, 'kompetensi_id' => 18], // QA Engineer - Quality Assurance
            ['lowongan_id' => 10, 'kompetensi_id' => 19], // Network Engineer - Network Engineering
            ['lowongan_id' => 11, 'kompetensi_id' => 6], // Data Engineer - Data Science
            ['lowongan_id' => 11, 'kompetensi_id' => 7], // Data Engineer - Manajemen Database
            ['lowongan_id' => 12, 'kompetensi_id' => 20], // IT Support - IT Support
            ['lowongan_id' => 13, 'kompetensi_id' => 4], // Cyber Security Analyst - Cyber Security
            ['lowongan_id' => 14, 'kompetensi_id' => 3], // Machine Learning Engineer - Machine Learning
            ['lowongan_id' => 15, 'kompetensi_id' => 8], // Cloud Engineer - Cloud Computing
            ['lowongan_id' => 16, 'kompetensi_id' => 10], // Business Intelligence - Business Intelligence
            ['lowongan_id' => 17, 'kompetensi_id' => 16], // Game Developer - Game Development
            ['lowongan_id' => 18, 'kompetensi_id' => 5], // Blockchain Developer - Blockchain Development
            ['lowongan_id' => 19, 'kompetensi_id' => 21], // Digital Marketing - Digital Marketing
            ['lowongan_id' => 20, 'kompetensi_id' => 22], // System Analyst - System Analysis
            ['lowongan_id' => 21, 'kompetensi_id' => 23], // IT Auditor - IT Audit
            ['lowongan_id' => 22, 'kompetensi_id' => 24], // SEO Specialist - SEO Specialist
            ['lowongan_id' => 23, 'kompetensi_id' => 25], // Social Media Specialist - Social Media Management
            ['lowongan_id' => 24, 'kompetensi_id' => 26], // IT Project Manager - IT Project Management
            ['lowongan_id' => 25, 'kompetensi_id' => 27], // Full Stack Developer - Full Stack Development
            ['lowongan_id' => 26, 'kompetensi_id' => 28], // IT Consultant - IT Consulting
            ['lowongan_id' => 27, 'kompetensi_id' => 29], // Penulis Teknologi - Technical Writing
            ['lowongan_id' => 28, 'kompetensi_id' => 30], // IT Trainer - IT Training
            ['lowongan_id' => 29, 'kompetensi_id' => 31], // Video Editor - Video Editing
            ['lowongan_id' => 30, 'kompetensi_id' => 7], // Database Administrator - Manajemen Database
            ['lowongan_id' => 31, 'kompetensi_id' => 32], // IT Researcher - IT Research
            ['lowongan_id' => 32, 'kompetensi_id' => 20], // IT Support Engineer - IT Support
            ['lowongan_id' => 33, 'kompetensi_id' => 33], // IT Documentation Specialist - Technical Documentation
            ['lowongan_id' => 34, 'kompetensi_id' => 34], // IT Procurement - IT Procurement
            ['lowongan_id' => 35, 'kompetensi_id' => 18], // IT Quality Assurance - Quality Assurance
            ['lowongan_id' => 36, 'kompetensi_id' => 4], // IT Security Engineer - Cyber Security
            ['lowongan_id' => 37, 'kompetensi_id' => 35], // IT Business Analyst - Business Analysis
            ['lowongan_id' => 38, 'kompetensi_id' => 36], // IT Infrastructure Engineer - IT Infrastructure
            ['lowongan_id' => 39, 'kompetensi_id' => 37], // IT Product Manager - Product Management
            ['lowongan_id' => 40, 'kompetensi_id' => 20], // IT Support Specialist - IT Support
            ['lowongan_id' => 41, 'kompetensi_id' => 38], // IT System Engineer - System Engineering
            ['lowongan_id' => 42, 'kompetensi_id' => 39], // IT Data Entry - Data Entry
            ['lowongan_id' => 43, 'kompetensi_id' => 20], // IT Helpdesk - IT Support
            ['lowongan_id' => 44, 'kompetensi_id' => 40], // IT Asset Management - Asset Management
            ['lowongan_id' => 45, 'kompetensi_id' => 41], // IT Compliance - IT Compliance
            ['lowongan_id' => 46, 'kompetensi_id' => 42], // IT Operations - IT Operations
            ['lowongan_id' => 47, 'kompetensi_id' => 30], // IT Trainer Assistant - IT Training
            ['lowongan_id' => 48, 'kompetensi_id' => 43], // IT Event Organizer - Event Management
            ['lowongan_id' => 49, 'kompetensi_id' => 44], // IT Customer Support - Customer Support
            ['lowongan_id' => 50, 'kompetensi_id' => 6], // IT Data Scientist - Data Science
            ['lowongan_id' => 51, 'kompetensi_id' => 45], // IT Product Designer - Product Design
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('m_lowongan_kompetensi')->insert($data);
        $this->command->info('Data lowongan kompetensi berhasil diimpor.');
    }
}
