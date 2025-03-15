<?php

namespace App\Livewire\Diskon;

use App\Models\Diskon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use App\Livewire\Diskon\DiskonTable;

class DiskonDelete extends Component
{
    #[Locked]
    public $id;

    public $modalDiskonDelete = false;


    #[Locked]
    public $kode_diskon;


    #[On('dispatch-diskon-table-delete')]
    public function set_Diskon($id,$kode_diskon)
    {
        $this->id = $id;
        $this->kode_diskon = $kode_diskon;
        $this->modalDiskonDelete = true;
    }

    public function del()
    {
        $del = Diskon::destroy($this->id);

        $del
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
        
        $this->dispatch('dispatch-diskon-delete-del')->to(DiskonTable::class);
        $this->modalDiskonDelete = false;
    }
    
    public function render()
    {
        return view('livewire..diskon.diskon-delete');
    }
}
