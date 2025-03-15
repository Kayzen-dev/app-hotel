<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nama',
        'no_tlpn',
        'alamat',
        'jenis_kelamin',
        'posisi',
        'gaji_pokok',
        'status_kerja',
        'tanggal_bergabung',
        'shift_kerja',
    ];
}
