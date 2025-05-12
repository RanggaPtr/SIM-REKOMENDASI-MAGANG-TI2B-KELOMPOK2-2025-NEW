<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerusahaanModel extends Model
{
    protected $table = 'm_table_perusahaan';

    protected $fillable = ['nama', 'lokasi_id', 'kontak', 'bidang_industri'];

    public function lokasi()
    {
        return $this->belongsTo(LokasiModel::class, 'lokasi_id');
    }

    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagangModel::class, 'perusahaan_id');
    }
}
