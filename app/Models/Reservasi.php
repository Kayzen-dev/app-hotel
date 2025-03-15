<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi';

    protected $fillable = [
        'no_reservasi',
        'id_tamu',
        'tanggal_check_in',
        'tanggal_check_out',
        'jumlah_kamar',
        'total_harga',
        'denda',
        'pajak',
        'status_reservasi',
        'keterangan',
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu');
    }



    public function pesanan() {
        return $this->hasMany(Pesanan::class, 'id_reservasi');
    }



    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_reservasi');
    }
}
