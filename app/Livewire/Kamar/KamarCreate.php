<?php

namespace App\Livewire\Kamar;

use App\Livewire\Forms\KamarForm;
use Livewire\Component;

class KamarCreate extends Component
{
    public KamarForm $form;

    public $modalKamarCreate = false;


    public function save()
    {
        $this->form->status_kamar = 'tersedia';

        $this->validate();
        $simpan = $this->form->store();

        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
        
        $this->form->reset();
        $this->dispatch('dispatch-kamar-create-save')->to(KamarTable::class);
        $this->modalKamarCreate = false;
    }

  
    
    
    public function render()
    {
        return view('livewire..kamar.kamar-create');
    }
}
