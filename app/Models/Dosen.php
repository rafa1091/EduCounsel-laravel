<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit sesuai database kamu
    protected $table = 'dosen'; 

    // Daftar kolom yang diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'nidn',
        'nama',
        'email',
        'ruangan',
        'no_hp',
        'status',
        'catatan',
        'foto'
    ];

    /**
     * 🌟 RELASI KE MODEL USER
     * Menghubungkan tabel dosen kembali ke tabel users 
     * melalui foreign key 'user_id'.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class,'dosen_id');
    }
}