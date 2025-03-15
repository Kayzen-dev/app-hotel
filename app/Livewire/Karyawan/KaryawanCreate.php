<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Livewire\Forms\KaryawanForm;

class KaryawanCreate extends Component
{
    public KaryawanForm $form;

    public $modalKaryawanCreate = false;


    
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
        $this->form->status_kerja = 'aktif';
        $this->form->gaji_pokok = $this->converDescimal($this->form->gaji_pokokRp);
        $this->validate();

        $simpan = $this->form->store();

        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
        
        $this->form->reset();
        $this->dispatch('dispatch-karyawan-create-save')->to(KaryawanTable::class);
        $this->modalKaryawanCreate = false;
    }

    public function render()
    {
        return view('livewire..karyawan.karyawan-create');
    }
}
