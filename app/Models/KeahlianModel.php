<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeahlianModel extends Model
{
    protected $table = 'm_keahlian';
    protected $primaryKey = 'keahlian_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['nama', 'deskripsi'];

    public function mahasiswaKeahlian()
    {
        return $this->hasMany(MahasiswaKeahlianModel::class, 'keahlian_id', 'keahlian_id');
    }

    public function lowonganKeahlian()
    {
        return $this->hasMany(LowonganKeahlianModel::class, 'keahlian_id', 'keahlian_id');
    }
}