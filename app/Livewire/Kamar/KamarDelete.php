<?php

namespace App\Livewire\Kamar;

use App\Models\JenisKamar;
use App\Models\Kamar;
use App\Models\Pesanan;
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

        $kam = Pesanan::where('id_kamar', $this->id)->get();
   
        if ($kam->isNotEmpty()) {
            $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus, kamar sedang digunakan');
            $this->modalKamarDelete = false;
            return;
        }

        $kamar = Kamar::find($this->id);
       
        $del = $kamar->delete();

        if ($del) {
           $jeniskamar = JenisKamar::find($kamar->id_jenis_kamar);

           $totalKamar =  Kamar::where('id_jenis_kamar', $jeniskamar->id)->count();

           $jeniskamar->update(
                [
                    'total_kamar' => $totalKamar
                ]   
            );

            $jeniskamar
            ? $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus')
            : $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
            $this->dispatch('dispatch-kamar-delete-del')->to(KamarTable::class);
            $this->modalKamarDelete = false;
            return;
        }else {
            $this->dispatch('notify', title: 'fail', message: 'Data gagal dihapus');
            $this->dispatch('dispatch-kamar-delete-del')->to(KamarTable::class);
            $this->modalKamarDelete = false;
            return;

        }

    }
    public function render()
    {
        return view('livewire..kamar.kamar-delete');
    }
}
