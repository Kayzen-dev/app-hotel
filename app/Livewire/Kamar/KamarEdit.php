<?php

namespace App\Livewire\Kamar;

use App\Models\Kamar;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\KamarForm;

class KamarEdit extends Component
{
    public KamarForm $form;

    public $modalKamarEdit = false;

    
    #[On('dispatch-kamar-table-edit')]
    public function set_Kamar(Kamar $id)
    {   
        $this->form->setKamar($id);
        $this->modalKamarEdit = true;
    }


    public function edit()
    {

        $update = $this->form->update($this->form->id);
        is_null($update)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal diupdate')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil diupdate');
        
        $this->form->reset();
        $this->dispatch('dispatch-kamar-update-edit')->to(KamarTable::class);
        $this->modalKamarEdit = false;
    }

    public function render()
    {
        return view('livewire..kamar.kamar-edit');
    }
}
