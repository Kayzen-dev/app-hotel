<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Diskon;
use Livewire\Attributes\Validate;

class DiskonForm extends Form
{
    public ?Diskon $diskon = null;
    public $id;

    #[Validate('required', message: ' wajib diisi')]
    #[Validate('unique:diskon,kode_diskon', message: 'Kode Diskon sudah terdaftar')]
    public $kode_diskon;

    #[Validate('required', message: 'Persentase wajib diisi')]
    public $persentase;

    #[Validate('required', message: 'Tanggal Mulai wajib diisi')]
    public $tanggal_mulai;

    #[Validate('required', message: 'Tanggal Berakhir wajib diisi')]
    public $tanggal_berakhir;

    #[Validate('required', message: 'Jenis Kamar wajib dipilih')]
    public $id_jenis_kamar;

    public $reset = false;


    public function resetData(){
        $this->kode_diskon = null;
        $this->persentase = null;
        $this->tanggal_mulai = null;
        $this->tanggal_berakhir = null;
        $this->reset = true;
    }


    public function setDiskon(Diskon $diskon)
    {
        $this->diskon = $diskon;

        $this->id = $diskon->id;
        $this->kode_diskon = $diskon->kode_diskon;
        $this->persentase = round($diskon->persentase);
        $this->tanggal_mulai = $diskon->tanggal_mulai;
        $this->tanggal_berakhir = $diskon->tanggal_berakhir;
        $this->id_jenis_kamar = $diskon->id_jenis_kamar;
    
    }

    public function store()
    {
        return Diskon::createDiskonIfNotExists([
            'kode_diskon' => $this->kode_diskon,
            'persentase' => $this->persentase,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'id_jenis_kamar' => $this->id_jenis_kamar
        ]);
        
        // $this->resetData();

        // return $diskon;


        // dd([
        //         'kode_diskon' => $this->kode_diskon,
        //         'persentase' => $this->persentase,
        //         'tanggal_mulai' => $this->tanggal_mulai,
        //         'tanggal_berakhir' => $this->tanggal_berakhir,
        //         'id_jenis_kamar' => $this->id_jenis_kamar,
        //         'reset' => $this->reset
        // ]);
    }

    public function update($id)
    {
        $this->validate(
            [
                'kode_diskon' => 'required|unique:diskon,kode_diskon,' . $id,
            ]
        );

        $Diskon = Diskon::findOrFail($id);

        $Diskon->update([
            'kode_diskon' => $this->kode_diskon,
            'persentase' => $this->persentase,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'id_jenis_kamar' => $this->id_jenis_kamar
        ]);

        return $Diskon;
    }


}
