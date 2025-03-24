<?php

namespace App\Livewire\Harga;

use App\Livewire\Forms\HargaForm;
use Livewire\Component;

class HargaCreate extends Component
{
    public HargaForm $form;

    public $modalHargaCreate = false;
    public $reset = false;

    public function save()
    {
        $this->validate();

        $this->reset = true;
        $simpan = $this->form->store();

        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
        
        $this->form->reset();
        $this->dispatch('dispatch-harga-create-save')->to(HargaTable::class);
        $this->modalHargaCreate = false;
    }
    
    public function render()
    {
        return view('livewire..harga.harga-create');
    }
}
