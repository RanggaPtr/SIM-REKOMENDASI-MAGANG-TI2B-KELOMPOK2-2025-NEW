<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinatModel extends Model
{
    protected $table = 'm_table_minat';

    protected $fillable = ['nama'];

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'minat_id');
    }
}