<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SilabusKonversiSksModel extends Model
{
    protected $table = 'm_silabus_konversi_sks';
    protected $primaryKey = 'silabus_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['lowongan_id', 'jumlah_sks', 'deskripsi', 'kriteria', 'dokumen_path'];

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id', 'lowongan_id');
    }
}