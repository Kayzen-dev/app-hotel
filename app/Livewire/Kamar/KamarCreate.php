<?php

namespace App\Livewire\Kamar;

use App\Models\Kamar;
use Livewire\Component;
use App\Models\JenisKamar;
use App\Livewire\Forms\KamarForm;
use App\Livewire\Kamar\KamarTable;

class KamarCreate extends Component
{
    public KamarForm $form;

    public $modalKamarCreate = false;


    public function save()
    {
        $this->form->status_kamar = 'tersedia';

        $this->validate();
        $simpan = $this->form->store();


        if (!is_null($simpan)) {
            $jeniskamar = JenisKamar::find($this->form->id_jenis_kamar);
 
            $totalKamar =  Kamar::where('id_jenis_kamar', $jeniskamar->id)->count();
 
            $jeniskamar->update(
                 [
                     'total_kamar' => $totalKamar
                 ]   
             );
 
             $jeniskamar
             ? $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan')
             : $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan');
             $this->dispatch('dispatch-kamar-delete-del')->to(KamarTable::class);
             $this->modalKamarCreate = false;
             return;
         }else {
            $this->form->reset();
            $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
            $this->dispatch('dispatch-kamar-create-save')->to(KamarTable::class);
            $this->modalKamarCreate = false;
            return;
 
         }

    }

  
    
    
    public function render()
    {
        return view('livewire..kamar.kamar-create');
    }
}
