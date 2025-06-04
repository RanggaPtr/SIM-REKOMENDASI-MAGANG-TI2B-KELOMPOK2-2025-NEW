<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaNotifikasiModel extends Model
{
    use HasFactory;
    protected $table = 't_mhs_notifikasi';
    protected $primaryKey = 'mhs_notifikasi_id';

    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'mahasiswa_id',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
