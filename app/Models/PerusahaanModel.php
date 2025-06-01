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

    public function getCalculatedRatingAttribute()
    {
        // Admin rating (default 0 if null)
        $adminRating = $this->rating ?? 0;

        // Get average feedback_rating dari relasi
        $feedbackAvg = PengajuanMagangModel::whereHas('lowongan', function ($q) {
            $q->where('perusahaan_id', $this->perusahaan_id);
        })->avg('feedback_rating');

        // Hitung bobot dinamis
        $hasAdmin = $this->rating !== null;
        $hasFeedback = $feedbackAvg !== null;

        if ($hasAdmin && $hasFeedback) {
            $adminWeight = 0.7;
            $feedbackWeight = 0.3;
        } elseif ($hasAdmin) {
            $adminWeight = 1.0;
            $feedbackWeight = 0.0;
        } elseif ($hasFeedback) {
            $adminWeight = 0.0;
            $feedbackWeight = 1.0;
        } else {
            return 0; // Tidak ada data rating sama sekali
        }

        // Hitung rata-rata tertimbang
        $combined = ($adminRating * $adminWeight) + (($feedbackAvg ?? 0) * $feedbackWeight);

        // Ubah ke persentase
        return round(($combined / 5) * 100, 2);
    }
}
