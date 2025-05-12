<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkemaModel extends Model
{
    protected $table = 'm_table_skema';

    protected $fillable = ['nama', 'deskripsi'];

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'skema_id');
    }

    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagangModel::class, 'skema_id');
    }
}