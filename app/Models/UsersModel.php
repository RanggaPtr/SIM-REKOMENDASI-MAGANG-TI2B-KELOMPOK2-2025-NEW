<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = 'm_table_users';

    protected $fillable = [
        'nama', 'email', 'password', 'role', 'foto_profile', 'no_telepon', 'alamat'
    ];

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'user_id');
    }

    public function dosen()
    {
        return $this->hasOne(DosenModel::class, 'user_id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitasModel::class, 'user_id');
    }
}
