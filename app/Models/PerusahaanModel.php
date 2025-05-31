<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerusahaanModel extends Model
{
    protected $table = 'm_perusahaan';
    protected $primaryKey = 'perusahaan_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'ringkasan',
        'deskripsi',
        'logo',
        'alamat',
        'wilayah_id',
        'kontak',
        'bidang_industri',
        'rating',
        'deskripsi_rating',
    ];

    public function lokasi()
    {
        return $this->belongsTo(WilayahModel::class, 'wilayah_id', 'wilayah_id');
    }

    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagangModel::class, 'perusahaan_id', 'perusahaan_id');
    }

    public function getCalculatedRatingAttribute() {
        // Weight configuration
        $adminWeight = 0.7;
        $feedbackWeight = 0.3;

        // Admin rating (default 0 if null)
        $adminRating = $this->rating ?? 0;

        // Get average feedback_rating from PengajuanMagangModel for this perusahaan
        $feedbackAvg = PengajuanMagangModel::whereHas('lowongan', function($q) {
            $q->where('perusahaan_id', $this->perusahaan_id);
        })->avg('feedback_rating');

        $feedbackAvg = $feedbackAvg ?? 0;

        // Weighted average
        $combined = ($adminRating * $adminWeight) + ($feedbackAvg * $feedbackWeight);

        // Return as percentage of max rating (5)
        return round(($combined / 5) * 100, 2);
    }
}