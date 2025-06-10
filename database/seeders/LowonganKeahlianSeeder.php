<?php

namespace Database\Seeders;

use App\Models\LowonganKeahlianModel;
use App\Models\LowonganMagangModel;
use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganKeahlianSeeder extends Seeder
{
    public function run()
    {
        // Format langsung pakai id urutan sesuai seeder
        $data = [
            // 1. Web Developer
            ['lowongan_id' => 1, 'keahlian_id' => 1], // Laravel
            ['lowongan_id' => 1, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 1, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 1, 'keahlian_id' => 38], // CSS
            ['lowongan_id' => 1, 'keahlian_id' => 39], // JavaScript

            // 2. Data Analyst
            ['lowongan_id' => 2, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 2, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 2, 'keahlian_id' => 14], // MongoDB

            // 3. Content Creator
            ['lowongan_id' => 3, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 3, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 3, 'keahlian_id' => 38], // CSS

            // 4. Mobile Developer
            ['lowongan_id' => 4, 'keahlian_id' => 13], // Flutter
            ['lowongan_id' => 4, 'keahlian_id' => 11], // Kotlin
            ['lowongan_id' => 4, 'keahlian_id' => 12], // Swift

            // 5. UI/UX Designer
            ['lowongan_id' => 5, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 5, 'keahlian_id' => 38], // CSS
            ['lowongan_id' => 5, 'keahlian_id' => 39], // JavaScript

            // 6. Backend Developer
            ['lowongan_id' => 6, 'keahlian_id' => 6], // Node.js
            ['lowongan_id' => 6, 'keahlian_id' => 7], // Express.js
            ['lowongan_id' => 6, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 6, 'keahlian_id' => 32], // PHP

            // 7. Front End Developer
            ['lowongan_id' => 7, 'keahlian_id' => 4], // React
            ['lowongan_id' => 7, 'keahlian_id' => 5], // Vue.js
            ['lowongan_id' => 7, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 7, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 7, 'keahlian_id' => 38], // CSS

            // 8. DevOps Engineer
            ['lowongan_id' => 8, 'keahlian_id' => 27], // Docker
            ['lowongan_id' => 8, 'keahlian_id' => 28], // Kubernetes
            ['lowongan_id' => 8, 'keahlian_id' => 24], // AWS

            // 9. QA Engineer
            ['lowongan_id' => 9, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 9, 'keahlian_id' => 3], // MySQL

            // 10. Network Engineer
            ['lowongan_id' => 10, 'keahlian_id' => 36], // C++
            ['lowongan_id' => 10, 'keahlian_id' => 34], // Java

            // 11. Data Engineer
            ['lowongan_id' => 11, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 11, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 11, 'keahlian_id' => 14], // MongoDB

            // 12. IT Support
            ['lowongan_id' => 12, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 12, 'keahlian_id' => 25], // Azure

            // 13. Cyber Security Analyst
            ['lowongan_id' => 13, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 13, 'keahlian_id' => 34], // Java

            // 14. Machine Learning Engineer
            ['lowongan_id' => 14, 'keahlian_id' => 2], // TensorFlow
            ['lowongan_id' => 14, 'keahlian_id' => 33], // Python

            // 15. Cloud Engineer
            ['lowongan_id' => 15, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 15, 'keahlian_id' => 25], // Azure
            ['lowongan_id' => 15, 'keahlian_id' => 27], // Docker

            // 16. Business Intelligence
            ['lowongan_id' => 16, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 16, 'keahlian_id' => 3], // MySQL

            // 17. Game Developer
            ['lowongan_id' => 17, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 17, 'keahlian_id' => 34], // Java
            ['lowongan_id' => 17, 'keahlian_id' => 36], // C++

            // 18. Blockchain Developer
            ['lowongan_id' => 18, 'keahlian_id' => 6], // Node.js
            ['lowongan_id' => 18, 'keahlian_id' => 39], // JavaScript

            // 19. Digital Marketing
            ['lowongan_id' => 19, 'keahlian_id' => 39], // JavaScript

            // 20. System Analyst
            ['lowongan_id' => 20, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 20, 'keahlian_id' => 32], // PHP

            // 21. IT Auditor
            ['lowongan_id' => 21, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 21, 'keahlian_id' => 32], // PHP

            // 22. SEO Specialist
            ['lowongan_id' => 22, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 22, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 22, 'keahlian_id' => 38], // CSS

            // 23. Social Media Specialist
            ['lowongan_id' => 23, 'keahlian_id' => 39], // JavaScript

            // 24. IT Project Manager
            ['lowongan_id' => 24, 'keahlian_id' => 32], // PHP
            ['lowongan_id' => 24, 'keahlian_id' => 3], // MySQL

            // 25. Full Stack Developer
            ['lowongan_id' => 25, 'keahlian_id' => 1], // Laravel
            ['lowongan_id' => 25, 'keahlian_id' => 4], // React
            ['lowongan_id' => 25, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 25, 'keahlian_id' => 3], // MySQL

            // 26. IT Consultant
            ['lowongan_id' => 26, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 26, 'keahlian_id' => 32], // PHP

            // 27. Penulis Teknologi
            ['lowongan_id' => 27, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 27, 'keahlian_id' => 37], // HTML

            // 28. IT Trainer
            ['lowongan_id' => 28, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 28, 'keahlian_id' => 39], // JavaScript

            // 29. Video Editor
            ['lowongan_id' => 29, 'keahlian_id' => 39], // JavaScript

            // 30. Database Administrator
            ['lowongan_id' => 30, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 30, 'keahlian_id' => 15], // PostgreSQL

            // 31. IT Researcher
            ['lowongan_id' => 31, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 31, 'keahlian_id' => 3], // MySQL

            // 32. IT Support Engineer
            ['lowongan_id' => 32, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 32, 'keahlian_id' => 25], // Azure

            // 33. IT Documentation Specialist
            ['lowongan_id' => 33, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 33, 'keahlian_id' => 38], // CSS

            // 34. IT Procurement
            ['lowongan_id' => 34, 'keahlian_id' => 3], // MySQL

            // 35. IT Quality Assurance
            ['lowongan_id' => 35, 'keahlian_id' => 39], // JavaScript

            // 36. IT Security Engineer
            ['lowongan_id' => 36, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 36, 'keahlian_id' => 34], // Java

            // 37. IT Business Analyst
            ['lowongan_id' => 37, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 37, 'keahlian_id' => 32], // PHP

            // 38. IT Infrastructure Engineer
            ['lowongan_id' => 38, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 38, 'keahlian_id' => 27], // Docker

            // 39. IT Product Manager
            ['lowongan_id' => 39, 'keahlian_id' => 39], // JavaScript
            ['lowongan_id' => 39, 'keahlian_id' => 37], // HTML

            // 40. IT Support Specialist
            ['lowongan_id' => 40, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 40, 'keahlian_id' => 25], // Azure

            // 41. IT System Engineer
            ['lowongan_id' => 41, 'keahlian_id' => 36], // C++
            ['lowongan_id' => 41, 'keahlian_id' => 34], // Java

            // 42. IT Data Entry
            ['lowongan_id' => 42, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 42, 'keahlian_id' => 38], // CSS

            // 43. IT Helpdesk
            ['lowongan_id' => 43, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 43, 'keahlian_id' => 25], // Azure

            // 44. IT Asset Management
            ['lowongan_id' => 44, 'keahlian_id' => 3], // MySQL

            // 45. IT Compliance
            ['lowongan_id' => 45, 'keahlian_id' => 33], // Python

            // 46. IT Operations
            ['lowongan_id' => 46, 'keahlian_id' => 24], // AWS
            ['lowongan_id' => 46, 'keahlian_id' => 27], // Docker

            // 47. IT Trainer Assistant
            ['lowongan_id' => 47, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 47, 'keahlian_id' => 39], // JavaScript

            // 48. IT Event Organizer
            ['lowongan_id' => 48, 'keahlian_id' => 39], // JavaScript

            // 49. IT Customer Support
            ['lowongan_id' => 49, 'keahlian_id' => 24], // AWS

            // 50. IT Data Scientist
            ['lowongan_id' => 50, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 50, 'keahlian_id' => 3], // MySQL

            // 51. IT Product Designer
            ['lowongan_id' => 51, 'keahlian_id' => 37], // HTML
            ['lowongan_id' => 51, 'keahlian_id' => 38], // CSS

            // ... lanjutkan untuk lowongan_id berikutnya sesuai pola di atas ...
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('m_lowongan_keahlian')->insert($data);
        $this->command->info('Data lowongan keahlian berhasil diimpor.');
    }
}
