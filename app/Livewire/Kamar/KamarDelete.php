<?php

namespace App\Livewire\Kamar;

use App\Models\Kamar;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class KamarDelete extends Component
{
    #[Locked]
    public $id;

    public $modalKamarDelete = false;


    #[Locked]
    public $no_kamar;


    #[On('dispatch-kamar-table-delete')]
    public function set_Kamar($id,$no_kamar)
    {
        $this->id = $id;
        $this->no_kamar = $no_kamar;
        $this->modalKamarDelete = true;
    }

    public function del()
    {   

       
        $del = Kamar::destroy($this->id);

        $del
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
        
        $this->dispatch('dispatch-kamar-delete-del')->to(KamarTable::class);
        $this->modalKamarDelete = false;
    }
    public function render()
    {
        return view('livewire..kamar.kamar-delete');
    }
}
