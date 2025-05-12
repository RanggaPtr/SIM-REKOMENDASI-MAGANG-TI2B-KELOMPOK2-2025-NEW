<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatDosenModel extends Model
{
    protected $table = 'm_table_sertifikat_dosen';

    protected $fillable = [
        'dosen_id', 'nama_sertifikat', 'penerbit', 'tanggal_terbit', 'file_sertifikat'
    ];

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }
}