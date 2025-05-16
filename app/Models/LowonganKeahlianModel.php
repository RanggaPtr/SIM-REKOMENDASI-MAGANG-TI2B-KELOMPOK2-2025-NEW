<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowonganKeahlianModel extends Model
{
    protected $table = 'm_lowongan_keahlian';
    protected $primaryKey = 'lowongan_keahlian_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['lowongan_id', 'keahlian_id'];

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id', 'lowongan_id');
    }

    public function keahlian()
    {
        return $this->belongsTo(KeahlianModel::class, 'keahlian_id', 'keahlian_id');
    }
}