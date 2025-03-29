<?php

namespace App\Models;

use App\Models\Kamar;
use Illuminate\Database\Eloquent\Model;

class nomorKamar extends Model
{
    protected $table = 'nomor_kamar';

    protected $fillable = [
        'id_kamar',
        'nomor_kamar'
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

}
