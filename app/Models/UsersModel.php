<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsersModel extends Authenticatable
{
    protected $table = 'm_users';
    protected $primaryKey = 'user_id'; // Disamakan dengan migrasi
    public $incrementing = true;
    public $timestamps = true;

   protected $fillable = ['nama', 'username', 'nim_nik', 'email', 'password', 'role', 'foto_profile', 'no_telepon', 'alamat'];

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function admin(): HasOne
    {
        return $this->hasOne(AdminModel::class, 'user_id', 'user_id');
    }

    public function dosen(): HasOne
    {
        return $this->hasOne(DosenModel::class, 'user_id', 'user_id');
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id', 'user_id');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitasModel::class, 'user_id', 'user_id');
    }

    public function getRoleName(): string
    {
        return $this->role;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function getAuthIdentifierName()
    {
        return 'user_id'; // Disamakan dengan primary key
    }
}