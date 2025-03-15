<?php

namespace App\Models;

use App\Models\Keluhan;
use App\Models\Reservasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamu';

    protected $fillable = [
        'nama',
        'alamat',
        'kota',
        'email',
        'no_tlpn',
        'no_identitas',
        'jumlah_anak',
        'jumlah_dewasa',
    ];

    public function keluhan()
    {
        return $this->hasMany(Keluhan::class, 'id_tamu');
    }

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_tamu');
    }
}
