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
        'perusahaan_id',
        'periode_id',
        'skema_id',
        'judul',
        'deskripsi',
        'persyaratan',
        'tanggal_buka',
        'tanggal_tutup',
        'tunjangan',
        'silabus_path',
        'kuota',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
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

    // Relasi ke tabel pivot keahlian
    public function lowonganKeahlian()
    {
        return $this->hasMany(LowonganKeahlianModel::class, 'lowongan_id', 'lowongan_id');
    }

    // Relasi ke tabel pivot kompetensi
    public function lowonganKompetensi()
    {
        return $this->hasMany(LowonganKompetensiModel::class, 'lowongan_id', 'lowongan_id');
    }

    // Relasi many-to-many langsung ke keahlian (optional, untuk kemudahan)
    public function keahlians()
    {
        return $this->belongsToMany(KeahlianModel::class, 'm_lowongan_keahlian', 'lowongan_id', 'keahlian_id');
    }

    // Relasi many-to-many langsung ke kompetensi (optional, untuk kemudahan)
    public function kompetensis()
    {
        return $this->belongsToMany(KompetensiModel::class, 'm_lowongan_kompetensi', 'lowongan_id', 'kompetensi_id');
    }

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

    public function getSelisihHari()
    {
        // Pastikan tanggal_buka bertipe Carbon (sudah di-cast di $casts)
        $tanggalBuka = $this->tanggal_buka;
        $hariIni = now();
        return $tanggalBuka->diffInDays($hariIni, false); // false agar hasil bisa negatif/positif
    }
}