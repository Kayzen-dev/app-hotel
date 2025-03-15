<?php

namespace App\Livewire\JenisKamar;

use App\Livewire\Forms\JenisKamarForm;
use Livewire\Component;

class JenisKamarCreate extends Component
{
    public JenisKamarForm $form;

    public $modalJenisKamarCreate = false;

    public function converDescimal($harga)
    {
        // Hapus "Rp " jika ada di depan string
        $harga = str_replace("Rp ", "", $harga);
    
        // Hapus titik sebagai pemisah ribuan
        $harga = str_replace(".", "", $harga);
    
        // Ganti koma dengan titik untuk desimal
        $harga = str_replace(",", ".", $harga);
    
        // Konversi ke format decimal (float)
        return number_format((float) $harga, 2, '.', '');
    }


    public function save()
    {
        
        $this->form->hargaKamar = $this->converDescimal($this->form->hargaRp);

        $this->validate();

        // dd($this->form);

        $simpan = $this->form->store();

        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
        
        $this->form->reset();
        $this->dispatch('dispatch-jenis-kamar-create-save')->to(JenisKamarTable::class);
        $this->modalJenisKamarCreate = false;
    }

    public function render()
    {
        return view('livewire..jenis-kamar.jenis-kamar-create');
    }
}
