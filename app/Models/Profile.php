<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'academic_profile', 'skills', 'certifications', 'experiences',
        'preferred_location', 'internship_type', 'cv_path', 'cover_letter_path', 'certificate_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
