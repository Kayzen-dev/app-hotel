<?php

namespace App\Livewire\DataTamu;

use Livewire\Component;
use App\Livewire\Forms\TamuForm;

class TamuCreate extends Component
{
    public TamuForm $form;

    public $modalTamuCreate = false;




    public function save()
    {
        $this->validate();

        $simpan = $this->form->store();

        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
        
        $this->form->reset();
        $this->dispatch('dispatch-tamu-create-save')->to(TamuTable::class);
        $this->modalTamuCreate = false;
    }
    
    public function render()
    {
        return view('livewire..data-tamu.tamu-create');
    }
}
