<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan_izin extends Model
{
    use HasFactory;
    protected $table = "pengajuan_izin";
    protected $fillable = [
        'id',
        'nip',
        'tgl_izin',
        'status',
        'keterangan',
        'status_approved',
    ];
}
