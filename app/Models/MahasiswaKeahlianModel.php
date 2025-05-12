<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaKeahlianModel extends Model
{
    protected $table = 'm_table_mahasiswa_keahlian';

    protected $fillable = ['mahasiswa_id', 'keahlian_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function keahlian()
    {
        return $this->belongsTo(KeahlianModel::class, 'keahlian_id');
    }
}
