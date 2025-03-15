<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $fillable = ['id_reservasi', 'id_kamar', 'id_harga', 'id_diskon', 'harga_kamar','harga_akhir', 'jumlah_malam', 'subtotal'];


    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'id_diskon');
    }

    public function harga()
    {
        return $this->belongsTo(Harga::class, 'id_harga');
    }

    public function reservasi() {
        return $this->belongsTo(Reservasi::class, 'id_reservasi');
    }

    public function kamar() {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }
}
