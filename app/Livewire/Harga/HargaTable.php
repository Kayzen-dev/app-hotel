<?php

namespace App\Livewire\Harga;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\HargaForm;
use App\Models\Harga;

class HargaTable extends Component
{
    use WithPagination, WithSorting;

    public HargaForm $form;

    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'harga.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default

    // Realtime proses
    #[On('dispatch-harga-create-save')]
    #[On('dispatch-harga-update-edit')]
    #[On('dispatch-harga-delete-del')]
    public function render()
    {
        return view('livewire..harga.harga-table',
        [
            'data' => Harga::where('id', 'like', '%' . $this->form->id . '%')
                ->with('jenisKamar')
                ->where('kode_harga', 'like', '%' . $this->form->kode_harga . '%')
                ->where('persentase_kenaikan_harga', 'like', '%' . $this->form->persentase_kenaikan_harga . '%')
                ->where('tanggal_mulai', 'like', '%' . $this->form->tanggal_mulai . '%')
                ->where('tanggal_berakhir', 'like', '%' . $this->form->tanggal_berakhir . '%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->paginate),
        ]
    );
    }
}
