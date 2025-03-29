<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';

    protected $fillable = [
        'no_kamar',
        'status_kamar',
        'harga_kamar',
        'id_jenis_kamar'
    ];

    // Relasi: Kamar milik satu JenisKamar
    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class, 'id_jenis_kamar');
    }


    public function nomorKamar()
    {
        return $this->hasOne(nomorKamar::class, 'id_kamar');
    }



    public function pesanan() {
        return $this->hasMany(Pesanan::class, 'id_kamar');
    }
    
}
