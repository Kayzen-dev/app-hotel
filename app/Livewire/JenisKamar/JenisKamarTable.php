<?php

namespace App\Livewire\JenisKamar;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\JenisKamarForm;
use App\Models\JenisKamar;

class JenisKamarTable extends Component
{
    use WithPagination, WithSorting;

    public JenisKamarForm $form;

    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'jenis_kamar.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default

    // Realtime proses
    #[On('dispatch-jenis-kamar-create-save')]
    #[On('dispatch-jenis-kamar-update-edit')]
    #[On('dispatch-jenis-kamar-delete-del')]

    public function render()
    {
        return view('livewire..jenis-kamar.jenis-kamar-table',
        [
            'data' => JenisKamar::where('id', 'like', '%' . $this->form->id . '%')
                ->with('diskon','harga')
                ->where('tipe_kamar', 'like', '%' . $this->form->tipe_kamar . '%')
                ->where('jenis_ranjang', 'like', '%' . $this->form->jenis_ranjang . '%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->paginate),
        ]
    );
    }
}
