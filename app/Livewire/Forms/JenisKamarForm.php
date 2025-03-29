<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Kamar;
use App\Models\JenisKamar;
use App\Models\nomorKamar;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

class JenisKamarForm extends Form
{
    public ?JenisKamar $jenisKamar = null;
    public $id;
    public $hargaRp;

    #[Validate('required', message: 'Tipe Kamar wajib diisi')]
    public $tipe_kamar;
    
    #[Validate('required', message: 'Jenis Ranjang wajib diisi')]
    public $jenis_ranjang;

    #[Validate('required', message: 'Jumlah Kamar wajib diisi')]
    public $jumlahKamar;

    #[Validate('required', message: 'Harga Kamar wajib diisi')]
    public $hargaKamar;

    #[Validate('required', message: 'Nomor Kamar wajib diisi')]
    public $no_kamar;



    public function setJenisKamar(JenisKamar $jenisKamar)
    {
        $this->jenisKamar = $jenisKamar;
        $this->id = $jenisKamar->id;
        $this->tipe_kamar = $jenisKamar->tipe_kamar;
        $this->jenis_ranjang = $jenisKamar->jenis_ranjang;
        
    }

    // public function store()
    // {
    //     $jumlahKamar = $this->jumlahKamar;
    //     return JenisKamar::create([
    //         'tipe_kamar' => $this->tipe_kamar,
    //         'jenis_ranjang' => $this->jenis_ranjang,
    //     ]);

    // }


        public function store()
        {
            
            // dd($this->hargaKamar);
            // Simpan Jenis Kamar terlebih dahulu
            $jenisKamar = JenisKamar::create([
                'tipe_kamar' => $this->tipe_kamar,
                'jenis_ranjang' => $this->jenis_ranjang,
                'total_kamar' => $this->jumlahKamar
            ]);
            
            $hargaKamar = $this->hargaKamar;

            // Ambil jumlah kamar dari input
            $jumlahKamar = $this->jumlahKamar;

            // Generate kamar berdasarkan jumlah yang diminta
            for ($i = 1; $i <= $jumlahKamar; $i++) {
                // Membuat nomor kamar dalam format KAMAR-001, KAMAR-002, dll
                $no_kamar = $this->no_kamar . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

                // Simpan data kamar
                Kamar::create([
                    'no_kamar' => $no_kamar,
                    'status_kamar' => 'tersedia', // Status awal kamar bisa di-set 'tersedia'
                    'id_jenis_kamar' => $jenisKamar->id, 
                    'harga_kamar'=> $hargaKamar
                ]);
            }


            // $nomor_kamar = DB::table('kamar')
            // ->where('id_jenis_kamar',  $jenisKamar->id)
            // ->where('status_kamar', 'tersedia')
            // ->select('no_kamar')
            // ->get()
            // ->map(function ($item) {
            //     return [
            //         'no_kamar' => $item->no_kamar,
            //         'status_no_kamar' => true // Set status_no_kamar menjadi true secara default
            //     ];
            // });
    
        // return response()->json([
        //     'nomor_kamar' => $nomor_kamar
        // ], 200, [], JSON_PRETTY_PRINT);

        // $no_json = json_encode([
        //             'nomor_kamar' => $nomor_kamar
        //         ], JSON_PRETTY_PRINT);


        //         dd($no_json);

            // $kamar = DB::table('kamar')->where('id_jenis_kamar', $jenisKamar->id)->first();


            // nomorKamar::create(
            //         [
            //             'id_kamar' => $kamar->id,
            //             'nomor_kamar' => $nomor_kamar
            //         ]
            //     );


            return $jenisKamar;
        }


        public function update($id)
        {

        
            $jenisKamar = JenisKamar::findOrFail($id);
        
            if ($this->hargaRp != null) {
                // Update semua kamar yang memiliki id_jenis_kamar yang sama
                Kamar::where('id_jenis_kamar', $id)->update([
                    'harga_kamar' => $this->hargaKamar
                ]);
            }
        
            $jenisKamar->update([
                'tipe_kamar' => $this->tipe_kamar,
                'jenis_ranjang' => $this->jenis_ranjang
            ]);
        
            return $jenisKamar;
        }
        
    public function delete()
    {
        return $this->jenisKamar->delete();
    }
}
