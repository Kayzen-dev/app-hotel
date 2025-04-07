<?php

namespace App\Livewire\DataTamu;

use App\Models\Tamu;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\TamuForm;


class TamuTable extends Component
{
    use WithPagination, WithSorting;

    public TamuForm $form;

    public $paginate = 10; // Jumlah data per halaman
    public $sortBy = 'tamu.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default



    // Realtime proses
    #[On('dispatch-tamu-create-save')]
    #[On('dispatch-tamu-update-edit')]
    #[On('dispatch-tamu-delete-del')]
    public function render()
    {
        return view('livewire..data-tamu.tamu-table',
        [
            'data' =>  Tamu::with(['reservasi.pesanan.kamar.jenisKamar','reservasi.pembayaran'])
            ->withCount('reservasi')
            ->where('nama', 'like', '%' . $this->form->nama . '%')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->paginate),
        ]
    );
    }
}
