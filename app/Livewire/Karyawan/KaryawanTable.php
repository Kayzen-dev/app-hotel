<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\KaryawanForm;
use App\Models\Karyawan;

class KaryawanTable extends Component
{
    use WithPagination, WithSorting;

    public KaryawanForm $form;

    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'karyawan.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default

    // Realtime proses
    #[On('dispatch-karyawan-create-save')]
    #[On('dispatch-karyawan-update-edit')]
    #[On('dispatch-karyawan-delete-del')]
    public function render()
    {
        return view('livewire..karyawan.karyawan-table',[
            'data' => Karyawan::where('id', 'like', '%' . $this->form->id . '%')
                ->where('nama', 'like', '%' . $this->form->nama . '%')
                ->where('no_tlpn', 'like', '%' . $this->form->no_tlpn . '%')
                ->where('alamat', 'like', '%' . $this->form->alamat . '%')
                ->where('jenis_kelamin', 'like', '%' . $this->form->jenis_kelamin . '%')
                ->where('posisi', 'like', '%' . $this->form->posisi . '%')
                ->where('gaji_pokok', 'like', '%' . $this->form->gaji_pokok . '%')
                ->where('status_kerja', 'like', '%' . $this->form->status_kerja . '%')
                ->where('tanggal_bergabung', 'like', '%' . $this->form->tanggal_bergabung . '%')
                ->where('shift_kerja', 'like', '%' . $this->form->shift_kerja . '%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->paginate),
        ]);
    }
}
