<?php

namespace App\Livewire\Diskon;

use Livewire\Component;
use App\Livewire\Forms\DiskonForm;
use App\Livewire\Diskon\DiskonTable;

class DiskonCreate extends Component
{
    public DiskonForm $form;

    public $modalDiskonCreate = false;

    public function save()
    {
        $this->validate();

        $simpan = $this->form->store();

        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
        
        $this->form->reset();
        $this->dispatch('dispatch-diskon-create-save')->to(DiskonTable::class);
        $this->modalDiskonCreate = false;
        $this->form->resetData();
    }
    
    public function render()
    {
        return view('livewire..diskon.diskon-create');
    }
}
