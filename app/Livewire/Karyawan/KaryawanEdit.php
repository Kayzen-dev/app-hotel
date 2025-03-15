<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use App\Livewire\Forms\KaryawanForm;

class KaryawanEdit extends Component
{
    
    public KaryawanForm $form;

    public $modalKaryawanEdit = false;
    
    #[On('dispatch-karyawan-table-edit')]
    public function set_Karyawan(Karyawan $id)
    {
        $this->form->setKaryawan($id);
        $this->modalKaryawanEdit = true;
    }


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

    public function edit()
    {
        $gajiPokok = $this->converDescimal($this->form->gaji_pokokRp);
        $this->form->gaji_pokok = $gajiPokok;
        $update = $this->form->update($this->form->id);

        is_null($update)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal diupdate')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil diupdate');
        
        $this->form->reset();
        $this->dispatch('dispatch-karyawan-update-edit')->to(KaryawanTable::class);
        $this->modalKaryawanEdit = false;
    }
    
    public function render()
    {
        return view('livewire..karyawan.karyawan-edit');
    }
}
