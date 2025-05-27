<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowonganMagangModel extends Model
{
    protected $table = 'm_lowongan_magang';
    protected $primaryKey = 'lowongan_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'perusahaan_id', 'periode_id', 'skema_id', 'judul', 'deskripsi',
        'persyaratan', 'minimal_ipk', 'tanggal_buka', 'tanggal_tutup', 'bidang_keahlian', 'tunjangan'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'minimal_ipk' => 'float',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan_id', 'perusahaan_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeMagangModel::class, 'periode_id', 'periode_id');
    }

    public function skema()
    {
        return $this->belongsTo(SkemaModel::class, 'skema_id', 'skema_id');
    }

    // Many-to-Many dengan keahlian
    public function keahlian()
    {
        return $this->belongsToMany(
            KeahlianModel::class,
            'm_lowongan_keahlian',
            'lowongan_id',
            'keahlian_id'
        );
    }

    // Many-to-Many dengan kompetensi
    public function kompetensi()
    {
        return $this->belongsToMany(
            KompetensiModel::class,
            'm_lowongan_kompetensi',
            'lowongan_id',
            'kompetensi_id'
        );
    }

    // Relasi lain
    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'lowongan_id', 'lowongan_id');
    }

    public function bookmark()
    {
        return $this->hasMany(BookmarkModel::class, 'lowongan_id', 'lowongan_id');
    }

    public function silabusKonversiSks()
    {
        return $this->hasOne(SilabusKonversiSksModel::class, 'lowongan_id', 'lowongan_id');
    }
}