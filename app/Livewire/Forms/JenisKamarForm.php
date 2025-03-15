<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Kamar;
use App\Models\JenisKamar;
use Livewire\Attributes\Validate;

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
                'jenis_ranjang' => $this->jenis_ranjang
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
