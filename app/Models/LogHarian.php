<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogHarian extends Model
{
    use HasFactory;

    protected $table = 't_log_aktivitas'; // Nama tabel sesuai database

    protected $fillable = [
        'tanggal',
        'kegiatan',
        'keterangan',
    ];

    // Jika kamu menggunakan timestamps di tabel, biarkan default true,
    // jika tidak ada kolom created_at dan updated_at, tambahkan ini:
    // public $timestamps = false;
}
