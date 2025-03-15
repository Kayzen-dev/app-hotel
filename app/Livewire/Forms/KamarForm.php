<?php

namespace App\Livewire\Forms;

use App\Models\Kamar;
use Livewire\Attributes\Validate;
use Livewire\Form;

class KamarForm extends Form
{
    public ?Kamar $kamar = null;
    public $id;
    public $no_kamar;

    #[Validate('required', message: 'Status Kamar wajib diisi')]
    public $status_kamar;

    #[Validate('required', message: 'Jenis Kamar wajib dipilih')]
    public $id_jenis_kamar;



    public function setKamar(Kamar $kamar)
    {
        $this->kamar = $kamar;

        $this->id = $kamar->id;
        $this->no_kamar = $kamar->no_kamar;
        $this->id_jenis_kamar = $kamar->id_jenis_kamar;
        $this->status_kamar = $kamar->status_kamar;
       
    }

    function formatCurrency($number)
    {
        // Menggunakan number_format untuk format angka
        return 'Rp ' . number_format($number, 2, ',', '.');
    }
    



    function noKamar($lastKodeKamar)
    {
        if ($lastKodeKamar) {
            // Ambil angka terakhir dari kode_kamar
            preg_match('/\d+$/', $lastKodeKamar, $matches);
            $lastNumber = isset($matches[0]) ? (int) $matches[0] : 0;
        } else {
            $lastNumber = 0;
        }

        // Increment angka
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // Ambil prefix sebelum angka (contoh: "KING-" dari "KING-010")
        $prefix = preg_replace('/\d+$/', '', $lastKodeKamar);

        // Buat kode baru (format: "KING-001", "KING-002", dst.)
        return strtoupper($prefix) . $newNumber;
    }

    public function store()
    {   

        // $kamar = Kamar::where('id_jenis_kamar',$this->id_jenis_kamar)->first();
        $kamar = Kamar::where('id_jenis_kamar', $this->id_jenis_kamar)
        ->orderBy('id', 'desc') // Mengurutkan dari ID terbesar
        ->first();        

        return Kamar::create([
            'no_kamar' => $this->noKamar($kamar->no_kamar),
            'id_jenis_kamar' => $this->id_jenis_kamar,
            'status_kamar' => $this->status_kamar,
            'harga_kamar' => $kamar->harga_kamar
        ]);

    }

    public function update($id)
    {
        $this->validate(
            [
                'no_kamar' => 'required|unique:kamar,no_kamar,' . $id,
            ]
        );

        $kamar = Kamar::findOrFail($id);

        $kamar->update([
            'id_jenis_kamar' => $this->id_jenis_kamar,
            'status_kamar' => $this->status_kamar
        ]);

        return $kamar;
    }

}
