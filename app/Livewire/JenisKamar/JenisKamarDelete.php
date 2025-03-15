<?php

namespace App\Livewire\JenisKamar;

use App\Models\JenisKamar;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class JenisKamarDelete extends Component
{
    #[Locked]
    public $id;

    public $modalJenisKamarDelete = false;


    #[Locked]
    public $tipe_kamar;


    #[On('dispatch-jenis-kamar-table-delete')]
    public function set_JenisKamar($id,$tipe_kamar)
    {
        $this->id = $id;
        $this->tipe_kamar = $tipe_kamar;
        $this->modalJenisKamarDelete = true;
    }

    public function del()
    {
        $del = JenisKamar::destroy($this->id);

        $del
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
        
        $this->dispatch('dispatch-jenis-kamar-delete-del')->to(JenisKamarTable::class);
        $this->modalJenisKamarDelete = false;
    }
    
    public function render()
    {
        return view('livewire..jenis-kamar.jenis-kamar-delete');
    }
}
