<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

class Harga extends Model
{
    use HasFactory;

    protected $table = 'harga';

    protected $fillable = [
        'kode_harga',
        'tanggal_mulai',
        'tanggal_berakhir',
        'persentase_kenaikan_harga',
        'id_jenis_kamar'

    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_harga');
    }

    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class, 'id_jenis_kamar');
    }
    /**
     * Validasi untuk memastikan tidak ada duplikasi harga berdasarkan kode_harga, tanggal_mulai, dan tanggal_berakhir
     */
    public static function validateHarga($data)
    {
        $validator = Validator::make($data, [
            'kode_harga' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Cek apakah validator gagal
        if ($validator->fails()) {
            return false;
        }

        // Memastikan tidak ada duplikasi untuk kode_harga, tanggal_mulai, dan tanggal_berakhir
        $exists = self::where('kode_harga', $data['kode_harga'])
                      ->where('tanggal_mulai', $data['tanggal_mulai'])
                      ->where('tanggal_berakhir', $data['tanggal_berakhir'])
                      ->exists();

        return $exists;
    }

    /**
     * Fungsi untuk membuat data harga jika tidak ada duplikasi
     */
    public static function createHargaIfNotExists($data)
    {
        // Cek apakah kombinasi kode_harga, tanggal_mulai, dan tanggal_berakhir sudah ada
        $exists = self::validateHarga($data);
        
        if (!$exists) {
            // Jika tidak ada, buat data baru
            return self::create($data);
        }

        return null; // Jika ada duplikasi, kembalikan null
    }

    /**
     * Fungsi untuk mengecek apakah harga masih berlaku
     * Menggunakan parameter tanggal yang bisa disesuaikan
     * Return boolean
     */
    public static function isHargaValidKode($kode_harga, $tanggal)
    {
        // Mengambil harga berdasarkan kode_harga
        $harga = self::where('kode_harga', $kode_harga)
                     ->where('tanggal_mulai', '<=', $tanggal)
                     ->where('tanggal_berakhir', '>=', $tanggal)
                     ->first();

        // Jika ada data yang cocok, maka harga masih berlaku
        return $harga ? true : false;
    }

    public static function isHargaValid($tanggal)
    {
        $tanggal = \Carbon\Carbon::parse($tanggal)->format('Y-m-d'); 
    
        $harga = self::whereDate('tanggal_mulai', '<=', $tanggal)
                     ->whereDate('tanggal_berakhir', '>=', $tanggal)
                     ->first();
    
        return $harga ?? null;
    }
    
    

}
