<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeMagangModel extends Model
{
    protected $table = 'm_periode_magang';

    protected $fillable = ['nama', 'tanggal_mulai', 'tanggal_selesai'];

    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagangModel::class, 'periode_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'periode_id');
    }
}
