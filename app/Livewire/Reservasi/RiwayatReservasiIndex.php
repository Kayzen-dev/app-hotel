<?php

namespace App\Livewire\Reservasi;

use App\Models\Reservasi;
use Livewire\Component;
use App\Models\Riwayat;
use Livewire\WithPagination;

class RiwayatReservasiIndex extends Component
{
    use WithPagination;

    public $selectedRiwayat = null;
    public $isOpen = false;

    public function showDetail($id)
    {
        $this->selectedRiwayat = Riwayat::find($id);
        $this->isOpen = true;
    }

    public function closeDetail()
    {
        $this->isOpen = false;
        $this->selectedRiwayat = null;
    }

    public function render()
    {
        return view('livewire.reservasi.riwayat-reservasi-index', [
            'data' => Reservasi::orderBy('id', 'desc')->paginate(10),
        ]);
    }
}
