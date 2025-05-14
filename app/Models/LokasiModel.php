<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiModel extends Model
{
    protected $table = 'm_lokasi';

    protected $fillable = ['nama'];

    public function perusahaan()
    {
        return $this->hasMany(PerusahaanModel::class, 'lokasi_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'lokasi_id');
    }
}
