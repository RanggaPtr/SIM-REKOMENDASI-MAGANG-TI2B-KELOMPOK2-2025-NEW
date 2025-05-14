<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanMagangModel extends Model
{
    protected $table = 't_pengajuan_magang';

    protected $fillable = ['mahasiswa_id', 'lowongan_id', 'dosen_id', 'periode_id', 'status'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeMagangModel::class, 'periode_id');
    }

    public function sertifikatMagang()
    {
        return $this->hasMany(SertifikatMagangModel::class, 'pengajuan_id');
    }

    public function evaluasiMagang()
    {
        return $this->hasMany(EvaluasiMagangModel::class, 'pengajuan_id');
    }
}
