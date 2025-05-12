<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowonganMagangModel extends Model
{
    protected $table = 'm_table_lowongan_magang';

    protected $fillable = [
        'perusahaan_id', 'periode_id', 'skema_id', 'judul', 'deskripsi',
        'persyaratan', 'bidang_keahlian', 'tanggal_buka', 'tanggal_tutup'
    ];

    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeMagangModel::class, 'periode_id');
    }

    public function skema()
    {
        return $this->belongsTo(SkemaModel::class, 'skema_id');
    }

    public function lowonganKompetensi()
    {
        return $this->hasMany(LowonganKompetensiModel::class, 'lowongan_id');
    }

    public function lowonganKeahlian()
    {
        return $this->hasMany(LowonganKeahlianModel::class, 'lowongan_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'lowongan_id');
    }

    public function bookmark()
    {
        return $this->hasMany(BookmarkModel::class, 'lowongan_id');
    }
}
