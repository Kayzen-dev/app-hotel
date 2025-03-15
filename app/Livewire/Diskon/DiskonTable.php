<?php

namespace App\Livewire\Diskon;

use App\Models\Diskon;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\DiskonForm;

class DiskonTable extends Component
{
    use WithPagination, WithSorting;

    public DiskonForm $form;

    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'diskon.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default

    // Realtime proses
    #[On('dispatch-diskon-create-save')]
    #[On('dispatch-diskon-update-edit')]
    #[On('dispatch-diskon-delete-del')]
    public function render()
    {
        return view('livewire..diskon.diskon-table',[
            'data' => Diskon::where('id', 'like', '%' . $this->form->id . '%')
                ->with('jenisKamar')
                ->where('kode_diskon', 'like', '%' . $this->form->kode_diskon . '%')
                ->where('persentase', 'like', '%' . $this->form->persentase . '%')
                ->where('tanggal_mulai', 'like', '%' . $this->form->tanggal_mulai . '%')
                ->where('tanggal_berakhir', 'like', '%' . $this->form->tanggal_berakhir . '%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->paginate),
        ]);
    }
}
