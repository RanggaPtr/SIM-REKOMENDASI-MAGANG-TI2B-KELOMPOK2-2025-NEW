<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatDosenModel extends Model
{
    protected $table = 'm_sertifikat_dosen';
    protected $primaryKey = 'sertifikat_dosen_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'dosen_id',
        'nama_sertifikat',
        'penerbit',
        'tanggal_terbit',
        'file_sertifikat'
    ];
    
    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];
    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
}
