<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'nisn',
        'kelas_id',
        'no_telepon',
        'alamat',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
