<?php

namespace App\Livewire\Harga;

use App\Models\Harga;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class HargaDelete extends Component
{
    #[Locked]
    public $id;

    public $modalHargaDelete = false;


    #[Locked]
    public $kode_harga;


    #[On('dispatch-harga-table-delete')]
    public function set_Harga($id,$kode_harga)
    {
        $this->id = $id;
        $this->kode_harga = $kode_harga;
        $this->modalHargaDelete = true;
    }

    public function del()
    {
        $del = Harga::destroy($this->id);

        $del
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
        
        $this->dispatch('dispatch-harga-delete-del')->to(HargaTable::class);
        $this->modalHargaDelete = false;
    }
    
    public function render()
    {
        return view('livewire..harga.harga-delete');
    }
}
