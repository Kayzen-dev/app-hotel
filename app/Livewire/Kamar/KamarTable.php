<?php

namespace App\Livewire\Kamar;

use App\Livewire\Forms\KamarForm;
use App\Models\Kamar;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class KamarTable extends Component
{
    use WithPagination, WithSorting;

    public KamarForm $form;


    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'kamar.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default
    public $idJenisKamar;

    // Realtime proses
    #[On('dispatch-kamar-create-save')]
    #[On('dispatch-kamar-update-edit')]
    #[On('dispatch-kamar-delete-del')]

    public function render()
    {
        return view('livewire.kamar.kamar-table', [
            'data' => Kamar::where('id', 'like', '%' . $this->form->id . '%')
                ->with(['jenisKamar'])
                ->where('no_kamar', 'like', '%' . $this->form->no_kamar . '%')
                ->where('id_jenis_kamar', 'like', '%' . $this->idJenisKamar . '%')
                ->where('status_kamar', 'like', '%' . $this->form->status_kamar . '%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->paginate),
        ]);
        

    }
}
