<?php

namespace App\Livewire\DataTamu;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use App\Models\Tamu;

class TamuDelete extends Component
{
    #[Locked]
    public $id;

    public $modalTamuDelete = false;

    #[Locked]
    public $nama;


    #[On('dispatch-tamu-table-delete')]
    public function set_Tamu($id,$nama)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->modalTamuDelete = true;
    }

    public function del()
    {
        $del = Tamu::destroy($this->id);

        $del
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
        
        $this->dispatch('dispatch-tamu-delete-del')->to(TamuTable::class);
        $this->modalTamuDelete = false;
    }

    
    public function render()
    {
        return view('livewire..data-tamu.tamu-delete');
    }
}
