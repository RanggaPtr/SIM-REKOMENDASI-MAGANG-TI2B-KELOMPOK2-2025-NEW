<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahModel extends Model
{

    
    protected $table = 'm_wilayah';

    protected $fillable = ['nama',];

    public function perusahaan()
    {
        return $this->hasMany(PerusahaanModel::class, 'wilayah_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'wilayah_id');
    }
}
