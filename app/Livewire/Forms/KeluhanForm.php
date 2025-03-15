<?php

namespace App\Livewire\Forms;

use App\Models\Keluhan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class KeluhanForm extends Form
{
    public ?Keluhan $keluhan = null;

    #[Validate('required', message: 'Keluhan wajib diisi')]
    public $isiKeluhan;

    #[Validate('required', message: 'Persentase wajib diisi')]
    public $status_keluhan;
    #[Validate('required', message: 'Tamu wajib dipilih')]
    public $tamu;


    public function store()
    {
        return Keluhan::create([
            'id_tamu' => $this->tamu,
            'keluhan' => $this->keluhan,
            'status_keluhan' => $this->status_keluhan
        ]);
    }

    public function delete()
    {
        return $this->keluhan->delete();
    }
}
