<?php

namespace App\Livewire\DataTamu;

use App\Models\Tamu;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\TamuForm;

class TamuEdit extends Component
{
    public TamuForm $form;

    public $modalTamuEdit = false;
    
    #[On('dispatch-tamu-table-edit')]
    public function set_Tamu(Tamu $id)
    {
        $this->form->setTamu($id);
        $this->modalTamuEdit = true;
    }



    public function edit()
    {
        // dd($this->form);
        $tamu = Tamu::find($this->form->id);

        $tamu->update([
            'nama' => $this->form->nama,
            'alamat' => $this->form->alamat,
            'no_tlpn' => $this->form->no_tlpn,
            'kota' => $this->form->kota,
            'email' => $this->form->email,
            'jumlah_anak' => $this->form->jumlah_anak,
            'jumlah_anak' => $this->form->jumlah_anak,
            'no_identitas' => $this->form->no_identitas,
        ]);

        is_null($tamu)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal diupdate')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil diupdate');
        
        $this->form->reset();
        $this->dispatch('dispatch-tamu-update-edit')->to(TamuTable::class);
        $this->modalTamuEdit = false;
    }

    public function render()
    {
        return view('livewire..data-tamu.tamu-edit');
    }
}
