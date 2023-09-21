<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    protected $table = "pengajuan_izin";
    protected $fillable = [
        'id',
        'nip',
        'tgl_presensi',
        'jam_masuk',
        'jam_keluar',
        'lokasi_in',
        'lokasi_out',
        'kode_jam_kerja',
        'status',
    ];
}
