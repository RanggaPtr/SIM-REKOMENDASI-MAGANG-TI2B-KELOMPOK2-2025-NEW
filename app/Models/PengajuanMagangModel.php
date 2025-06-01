<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanMagangModel extends Model
{
    protected $table = 't_pengajuan_magang';
    protected $primaryKey = 'pengajuan_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['mahasiswa_id', 'lowongan_id', 'status', 'feedback_rating', 'feedback_deskripsi'];

    protected $casts = [
        'status' => 'string',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id', 'lowongan_id');
    }


    public function sertifikatMagang()
    {
        return $this->hasMany(SertifikatMagangModel::class, 'pengajuan_id', 'pengajuan_id');
    }

    public function evaluasiMagang()
    {
        return $this->hasMany(EvaluasiMagangModel::class, 'pengajuan_id', 'pengajuan_id');
    }
}