<?php

namespace App\Livewire\Diskon;

use App\Models\Diskon;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\DiskonForm;

class DiskonEdit extends Component
{
    public DiskonForm $form;

    public $modalDiskonEdit = false;
    
    public $reset = false;

    #[On('dispatch-diskon-table-edit')]
    public function set_Diskon(Diskon $id)
    {
        $this->form->setDiskon($id);
        $this->modalDiskonEdit = true;
    }

    public function edit()
    {
        $this->reset = true;

        $update = $this->form->update($this->form->id);

        is_null($update)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal diupdate')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil diupdate');
        
        $this->form->reset();
        $this->dispatch('dispatch-diskon-update-edit')->to(DiskonTable::class);
        $this->modalDiskonEdit = false;
        $this->form->resetData();

    }

    public function render()
    {
        return view('livewire..diskon.diskon-edit');
    }
}
