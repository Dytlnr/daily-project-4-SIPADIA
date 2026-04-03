<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'tahun_masuk',
        'tanggal_lulus',
        'fakultas',
        'program_studi',
        'email',
        'no_hp',
        'linkedin',
        'instagram',
        'facebook',
        'tiktok',
        'tempat_kerja',
        'alamat_kerja',
        'posisi',
        'status_kerja',
        'sosmed_tempat_kerja',
        'consent',
        'source_hash',
    ];

    protected $casts = [
        'consent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
