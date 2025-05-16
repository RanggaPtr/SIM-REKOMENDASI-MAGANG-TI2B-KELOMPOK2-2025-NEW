<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkemaModel extends Model
{
    protected $table = 'm_skema';
    protected $primaryKey = 'skema_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['nama', 'deskripsi'];

    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagangModel::class, 'skema_id', 'skema_id');
    }
}