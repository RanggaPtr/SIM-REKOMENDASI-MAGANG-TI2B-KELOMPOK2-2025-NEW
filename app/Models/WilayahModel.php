<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahModel extends Model
{

    protected $table = 'm_wilayah';
    protected $primaryKey = 'wilayah_id'; // Sesuaikan dengan migrasi
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['nama', 'kode_wilayah', 'latitude', 'longitude'];

    public function perusahaan()
    {
        return $this->hasMany(PerusahaanModel::class, 'wilayah_id', 'wilayah_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'wilayah_id', 'wilayah_id');
    }
}
