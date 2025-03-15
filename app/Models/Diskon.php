<?php

namespace App\Models;

use App\Models\Reservasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

class Diskon extends Model
{
    use HasFactory;

    protected $table = 'diskon';

    protected $fillable = [
        'kode_diskon',
        'persentase',
        'tanggal_mulai',
        'tanggal_berakhir',
        'id_jenis_kamar'
    ];

    /**
     * Relasi dengan model Reservasi
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_diskon');
    }

    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class, 'id_jenis_kamar');
    }

    /**
     * Validasi untuk memastikan tidak ada duplikasi diskon berdasarkan kode_diskon, tanggal_mulai, dan tanggal_berakhir
     */
    public static function validateDiskon($data)
    {
        $validator = Validator::make($data, [
            'kode_diskon' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'persentase' => 'required|numeric|min:0',
        ]);

        // Cek apakah validator gagal
        if ($validator->fails()) {
            return $validator->errors();
        }

        // Memastikan tidak ada duplikasi untuk kode_diskon, tanggal_mulai, dan tanggal_berakhir
        $exists = self::where('kode_diskon', $data['kode_diskon'])
                      ->where('tanggal_mulai', $data['tanggal_mulai'])
                      ->where('tanggal_berakhir', $data['tanggal_berakhir'])
                      ->exists();

        return $exists;
    }

    /**
     * Fungsi untuk membuat data diskon jika tidak ada duplikasi
     */
    public static function createDiskonIfNotExists($data)
    {
        // Cek apakah kombinasi kode_diskon, tanggal_mulai, dan tanggal_berakhir sudah ada
        $exists = self::validateDiskon($data);
        
        if (!$exists) {
            // Jika tidak ada, buat data baru
            return self::create($data);
        }

        return null; // Jika ada duplikasi, kembalikan null
    }

    /**
     * Fungsi untuk mengecek apakah diskon masih berlaku
     * Menggunakan parameter tanggal yang bisa disesuaikan
     * Return boolean
     */
    public static function isDiskonValidKode($kode_diskon, $tanggal)
    {
        // Mengambil diskon berdasarkan kode_diskon
        $diskon = self::where('kode_diskon', $kode_diskon)
                      ->where('tanggal_mulai', '<=', $tanggal)
                      ->where('tanggal_berakhir', '>=', $tanggal)
                      ->first();

        // Jika ada data yang cocok, maka diskon masih berlaku
        return $diskon ? true : false;
    }


    public static function isDiskonValid($tanggal, $jenisKamarId)
    {
        $tanggal = \Carbon\Carbon::parse($tanggal)->format('Y-m-d'); 
    
        // Mengambil diskon berdasarkan id_jenis_kamar dan tanggal
        $diskon = self::whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_berakhir', '>=', $tanggal)
            ->where('id_jenis_kamar', $jenisKamarId) // Menambahkan kondisi berdasarkan id_jenis_kamar
            ->first();
    
        // Jika ada data yang cocok, maka diskon masih berlaku
        return $diskon ?? null;
    }
    

}
