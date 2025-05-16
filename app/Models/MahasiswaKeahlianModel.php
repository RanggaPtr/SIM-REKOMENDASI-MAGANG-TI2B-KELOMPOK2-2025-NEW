<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaKeahlianModel extends Model
{
    protected $table = 'm_mahasiswa_keahlian';
    protected $primaryKey = 'mahasiswa_keahlian_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['mahasiswa_id', 'keahlian_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function keahlian()
    {
        return $this->belongsTo(KeahlianModel::class, 'keahlian_id', 'keahlian_id');
    }
}