<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeahlianModel extends Model
{
    protected $table = 'm_table_keahlian';

    protected $fillable = ['nama', 'deskripsi'];

    public function mahasiswaKeahlian()
    {
        return $this->hasMany(MahasiswaKeahlianModel::class, 'keahlian_id');
    }

    public function lowonganKeahlian()
    {
        return $this->hasMany(LowonganKeahlianModel::class, 'keahlian_id');
    }
}
