<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    protected $table = 'm_table_dosen';

    protected $fillable = ['user_id', 'nidn', 'program_studi_id', 'jumlah_bimbingan'];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudiModel::class, 'program_studi_id');
    }

    public function sertifikatDosen()
    {
        return $this->hasMany(SertifikatDosenModel::class, 'dosen_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'dosen_id');
    }
}
