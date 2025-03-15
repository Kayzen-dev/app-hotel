<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_reservasi',
        'id_user',
        'jumlah_pembayaran',
        'kembalian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi');
    }
}

