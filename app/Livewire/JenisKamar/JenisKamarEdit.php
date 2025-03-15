<?php

namespace App\Livewire\JenisKamar;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\JenisKamarForm;
use App\Models\JenisKamar;

class JenisKamarEdit extends Component
{
    public JenisKamarForm $form;

    public $modalJenisKamarEdit = false;
    
    #[On('dispatch-jenis-kamar-table-edit')]
    public function set_JenisKamar(JenisKamar $id)
    {
        $this->form->setJenisKamar($id);
        $this->modalJenisKamarEdit = true;
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
        $this->form->hargaKamar = $this->converDescimal($this->form->hargaRp);

        
        // dd($this->form);
        $update = $this->form->update($this->form->id);

        is_null($update)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal diupdate')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil diupdate');
        
        $this->form->reset();
        $this->dispatch('dispatch-jenis-kamar-update-edit')->to(JenisKamarTable::class);
        $this->modalJenisKamarEdit = false;
    }
    
    public function render()
    {
        return view('livewire..jenis-kamar.jenis-kamar-edit');
    }
}
