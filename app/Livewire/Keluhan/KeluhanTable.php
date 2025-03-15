<?php

namespace App\Livewire\Keluhan;

use App\Models\Keluhan;
use Livewire\Component;

class KeluhanTable extends Component
{






    public function selesai($id){

        $keluhan = Keluhan::find($id);
        $keluhan->update([
            'status_keluhan' => 'selesai'
        ]);

        is_null($keluhan)
        ? $this->dispatch('notify', title: 'fail', message: 'Keluhan gagal diselesaikan')
        : $this->dispatch('notify', title: 'success', message: 'Keluhan berhasil diselesaikan');
        return ;
    }


    public function hapus($id){

        $keluhan = Keluhan::destroy($id);

        is_null($keluhan)
        ? $this->dispatch('notify', title: 'fail', message: 'Keluhan gagal dihapus')
        : $this->dispatch('notify', title: 'success', message: 'Keluhan berhasil dihapus');
        return ;
    }




    public function render()
    {
        $data = Keluhan::all();
        return view('livewire..keluhan.keluhan-table', compact('data'));
    }
}
