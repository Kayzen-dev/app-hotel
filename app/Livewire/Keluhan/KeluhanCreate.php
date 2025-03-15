<?php

namespace App\Livewire\Keluhan;

use App\Models\Keluhan;
use Livewire\Component;

class KeluhanCreate extends Component
{

    public $modalCreate = false;
    public $keluhan;
    public $namaTamu;
    public $status_keluhan = 'diproses';

    protected $rules = [
        'keluhan' => 'required|string',
        'status_keluhan' => 'required|in:diproses,selesai',
    ];

    public function save()
    {
        $this->validate();

        $keluhan = Keluhan::create([
            'nama_tamu' => $this->namaTamu,
            'keluhan' => $this->keluhan,
            'status_keluhan' => 'diproses',
        ]);

        
        is_null($keluhan)
        ? $this->dispatch('notify', title: 'fail', message: 'Keluhan gagal ditambahkan')
        : $this->dispatch('notify', title: 'success', message: 'Keluhan berhasil ditambahkan');
        $this->modalCreate = false;
        $this->reset();
        return ;
    }

  


    public function render()
    {
        return view('livewire..keluhan.keluhan-create');
    }
}
