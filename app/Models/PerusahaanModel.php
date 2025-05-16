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
}