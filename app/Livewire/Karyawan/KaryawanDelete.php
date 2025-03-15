<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class KaryawanDelete extends Component
{
    #[Locked]
    public $id;

    public $modalKaryawanDelete = false;


    #[Locked]
    public $nama;


    #[On('dispatch-karyawan-table-delete')]
    public function set_Karyawan($id,$nama)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->modalKaryawanDelete = true;
    }

    public function del()
    {
        $del = Karyawan::destroy($this->id);

        $del
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
        
        $this->dispatch('dispatch-karyawan-delete-del')->to(KaryawanTable::class);
        $this->modalKaryawanDelete = false;
    }

    
    public function render()
    {
        return view('livewire..karyawan.karyawan-delete');
    }
}
