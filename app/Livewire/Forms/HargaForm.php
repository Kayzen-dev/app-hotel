<?php

namespace App\Livewire\Forms;

use App\Models\Harga;
use Livewire\Attributes\Validate;
use Livewire\Form;

class HargaForm extends Form
{
    public ?Harga $harga = null;
    public $id;
    public $reset = false;


    

    

    #[Validate('required', message: 'Kode Harga wajib diisi')]
    #[Validate('unique:harga,kode_harga', message: 'Kode Harga sudah terdaftar')]
    public $kode_harga;
    
    #[Validate('required', message: 'Tanggal Mulai wajib diisi')]
    public $tanggal_mulai;

    #[Validate('required', message: 'Tanggal Berakhir wajib diisi')]
    public $tanggal_berakhir;

    #[Validate('required', message: 'persentase_kenaikan_harga wajib diisi')]
    public $persentase_kenaikan_harga;


    #[Validate('required', message: 'Jenis Kamar wajib dipilih')]
    public $id_jenis_kamar;



    public function setHarga(Harga $harga)
    {
        $this->harga = $harga;

        $this->id = $harga->id;
        $this->kode_harga = $harga->kode_harga;
        $this->persentase_kenaikan_harga = round($harga->persentase_kenaikan_harga);
        $this->tanggal_mulai = $harga->tanggal_mulai;
        $this->tanggal_berakhir = $harga->tanggal_berakhir;
        $this->id_jenis_kamar = $harga->id_jenis_kamar;
    
    }

    public function store()
    {
        $harga = Harga::createHargaIfNotExists([
            'kode_harga' => $this->kode_harga,
            'persentase_kenaikan_harga' => $this->persentase_kenaikan_harga,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'id_jenis_kamar' => $this->id_jenis_kamar
        ]);


        return $harga;
    }



    public function resetData(){
        $this->kode_harga = null;
        $this->persentase_kenaikan_harga = null;
        $this->tanggal_mulai = null;
        $this->tanggal_berakhir = null;
        $this->reset = true;
    }

    public function update($id)
    {
        $this->validate(
            [
                'kode_harga' => 'required|unique:harga,kode_harga,' . $id,
            ]
        );

        $harga = Harga::findOrFail($id);
     
        $harga->update([
            'kode_harga' => $this->kode_harga,
            'persentase_kenaikan_harga' => $this->persentase_kenaikan_harga,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'id_jenis_kamar' => $this->id_jenis_kamar
        ]);

        return $harga;
    }

}
