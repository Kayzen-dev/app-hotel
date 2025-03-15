<?php

namespace App\Livewire\Harga;

use App\Models\Harga;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\HargaForm;

class HargaEdit extends Component
{
    public HargaForm $form;

    public $modalHargaEdit = false;
    
    #[On('dispatch-harga-table-edit')]
    public function set_Harga(Harga $id)
    {
        $this->form->setHarga($id);
        $this->modalHargaEdit = true;
    }

    public function edit()
    {
        $update = $this->form->update($this->form->id);

        is_null($update)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal diupdate')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil diupdate');
        
        $this->form->reset();
        $this->dispatch('dispatch-harga-update-edit')->to(HargaTable::class);
        $this->modalHargaEdit = false;
    }

    public function render()
    {
        return view('livewire..harga.harga-edit');
    }
}
