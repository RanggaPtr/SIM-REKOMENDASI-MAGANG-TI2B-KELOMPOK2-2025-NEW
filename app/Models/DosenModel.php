<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    protected $table = 'm_dosen';
    protected $primaryKey = 'dosen_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['user_id', 'nik', 'prodi_id', 'jumlah_bimbingan'];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudiModel::class, 'prodi_id', 'prodi_id');
    }

    public function sertifikatDosen()
    {
        return $this->hasMany(SertifikatDosenModel::class, 'dosen_id', 'dosen_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'dosen_id', 'dosen_id');
    }
}