<?php

namespace App\Models;

use App\Models\Kamar;
use Illuminate\Database\Eloquent\Model;

class JenisKamar extends Model
{
    protected $table = 'jenis_kamar';

    protected $fillable = [
    'tipe_kamar',
        'jenis_ranjang'
    ];


    
    // public function diskon()
    // {
    //     return $this->hasOne(Diskon::class, 'id_jenis_kamar');
    // }

    // public function harga()
    // {
    //     return $this->hasOne(Harga::class, 'id_jenis_kamar');
    // }

    public function diskon()
    {
        return $this->hasMany(Diskon::class, 'id_jenis_kamar');
    }

    public function harga()
    {
        return $this->hasMany(Harga::class, 'id_jenis_kamar');
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_jenis_kamar');
    }
}
