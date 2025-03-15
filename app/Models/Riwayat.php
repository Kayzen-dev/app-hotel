<?php

namespace App\Models;

use App\Models\Reservasi;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat';

    protected $fillable = [
        'id_reservasi',
        'id_pembayaran',
        'no_reservasi',
        'tanggal_check_in',
        'tanggal_check_out',
        'total_harga',
        'jumlah_pembayaran',
        'denda',
        'status_reservasi',
        'keterangan',
    ];

    /**
     * Relasi ke tabel Reservasi.
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi');
    }

    /**
     * Relasi ke tabel Pembayaran.
     */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran');
    }

}
