<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Tamu;
use Livewire\Attributes\Validate;

class TamuForm extends Form
{
    public ?Tamu $tamu = null;
    public $id;

    #[Validate('required|string|min:3')]
    public $nama;

    #[Validate('required|string')]
    public $alamat;

    #[Validate('required|string')]
    public $kota;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|string')]
    public $no_tlpn;

    #[Validate('required|string')]
    public $no_identitas;

    #[Validate('required|integer|min:0')]
    public $jumlah_anak = 0;

    #[Validate('required|integer|min:1')]
    public $jumlah_dewasa = 1;

    public function setTamu(Tamu $tamu)
    {
        $this->tamu = $tamu;
        $this->id = $tamu->id;
        $this->nama = $tamu->nama;
        $this->alamat = $tamu->alamat;
        $this->kota = $tamu->kota;
        $this->email = $tamu->email;
        $this->no_tlpn = $tamu->no_tlpn;
        $this->no_identitas = $tamu->no_identitas;
        $this->jumlah_anak = $tamu->jumlah_anak;
        $this->jumlah_dewasa = $tamu->jumlah_dewasa;
    }

    public function store()
    {
        $this->validate();
        return Tamu::create($this->only([
            'nama', 'alamat', 'kota', 'email',
            'no_tlpn', 'no_identitas', 
            'jumlah_anak', 'jumlah_dewasa'
        ]));
    }

    public function update($id)
    {
        $this->validate([
            'email' => 'required|email|unique:tamu,email,'.$id,
            'no_identitas' => 'required|unique:tamu,no_identitas,'.$id,
        ]);

        $tamu = Tamu::find($id);

        // $tamu->update([
        //     'nama' => $this->nama,
        //     'email' => $this->email,
        //     'no_identitas' => $this->no_identitas,
        //     ''
        // ]);


        return $tamu;
    }

    public function delete()
    {
        return $this->tamu->delete();
    }
}