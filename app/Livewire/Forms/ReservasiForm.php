<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Riwayat;
use App\Models\Reservasi;
use Livewire\Attributes\Validate;

class ReservasiForm extends Form
{
    public ?Reservasi $reservasi = null;
    public $id;
    public $no_reservasi;

    
    #[Validate('required', message: 'id_tamu wajib diisi')]
    public $id_tamu;
    
    #[Validate('required', message: 'id_kamar wajib diisi')]
    public $id_kamar;
    
    #[Validate('nullable', message: 'id_diskon wajib diisi')]
    public $id_diskon;

    #[Validate('nullable', message: 'id_harga wajib diisi')]
    public $id_harga;
    

    #[Validate('required', message: 'tanggal_check_in wajib diisi')]
    public $tanggal_check_in;
    
    #[Validate('required', message: 'tanggal_check_out wajib diisi')]
    public $tanggal_check_out;
    

    #[Validate('required', message: 'total_harga wajib diisi')]
    public $total_harga;
    

    #[Validate('nullable', message: 'denda wajib diisi')]
    public $denda;

    #[Validate('nullable', message: 'denda wajib diisi')]
    public $pajak;
    

    #[Validate('required', message: 'status_reservasi wajib diisi')]
    public $status_reservasi;
    

    #[Validate('required', message: 'keterangan wajib diisi')]
    public $keterangan;
    



    private function buatNoReservasi()
    {
        do {
            $no_reservasi = rand(100000000000000000, 999999999999999999);  
        } while (Reservasi::where('no_reservasi', $no_reservasi)->exists()); 
    
        return $no_reservasi;
    }




    public function setReservasi(Reservasi $reservasi)
    {
        $this->reservasi = $reservasi;
        $this->id = $reservasi->id;
        $this->no_reservasi = $reservasi->no_reservasi;
        $this->id_tamu = $reservasi->id_tamu;
        $this->id_diskon = $reservasi->id_diskon;
        $this->tanggal_check_in = $reservasi->tanggal_check_in;
        $this->tanggal_check_out = $reservasi->tanggal_check_out;
        $this->total_harga = $reservasi->total_harga;
        $this->denda = $reservasi->denda;
        $this->status_reservasi = $reservasi->status_reservasi;
        $this->keterangan = $reservasi->keterangan;
    }

    public function store()
    {
        return Reservasi::create([
            'no_reservasi' => $this->buatNoReservasi(),
            'id_tamu' => $this->id_tamu,
            'id_kamar' => $this->id_kamar,
            'id_diskon' => $this->id_diskon,
            'tanggal_check_in' => $this->tanggal_check_in,
            'tanggal_check_out' => $this->tanggal_check_out,
            'total_harga' => $this->total_harga,
            'denda' => $this->denda,
            'status_reservasi' => $this->status_reservasi,
            'keterangan' => $this->keterangan
        ]);
    }

    public function update($id)
    {
        $this->validate();

        $reservasi = Reservasi::findOrFail($id);

        $reservasi->update([
            'no_reservasi' => $this->buatNoReservasi(),
            'id_tamu' => $this->id_tamu,
            'id_kamar' => $this->id_kamar,
            'id_diskon' => $this->id_diskon,
            'tanggal_check_in' => $this->tanggal_check_in,
            'tanggal_check_out' => $this->tanggal_check_out,
            'total_harga' => $this->total_harga,
            'denda' => $this->denda,
            'status_reservasi' => $this->status_reservasi,
            'keterangan' => $this->keterangan
        ]);

        return $reservasi;
    }


    public function buatRiwayatReservasi($id) {
        $reservasi = Reservasi::with('pembayaran')->findOrFail($id);
    
        return Riwayat::create([
            'id_reservasi' => $reservasi->id,
            'id_pembayaran' => $reservasi->pembayaran->id ?? null,
            'id_tamu' => $reservasi->id_tamu,
            'id_kamar' => $reservasi->id_kamar,
            'no_reservasi' => $reservasi->no_reservasi,
            'tanggal_check_in' => $reservasi->tanggal_check_in,
            'tanggal_check_out' => $reservasi->tanggal_check_out,
            'total_harga' => $reservasi->total_harga,
            'jumlah_pembayaran' => $reservasi->pembayaran->jumlah_pembayaran ?? null,
            'denda' => $reservasi->denda,
            'status_reservasi' => 'selesai',
            'metode_pembayaran' => $reservasi->pembayaran->metode_pembayaran ?? null,
            'keterangan' => $reservasi->keterangan,
        ]);
    }

    // public function buatPembayaran($id)
    // {
    //     $reservasi = Reservasi::findOrFail($id);

    //     $pembayaran = Pembayaran::create([
    //         'id_reservasi' => $reservasi->id,
    //         'id_user' => auth()->id(),  // Mengambil ID user yang sedang login
    //         'jumlah_pembayaran' => $request->jumlah_pembayaran,
    //         'kembalian' => $request->kembalian,
    //         'metode_pembayaran' => $request->metode_pembayaran
    //     ]);

    //     return response()->json(['message' => 'Pembayaran berhasil dibuat', 'pembayaran' => $pembayaran]);
    // }



    public function delete()
    {
        return $this->reservasi->delete();
    }


}
