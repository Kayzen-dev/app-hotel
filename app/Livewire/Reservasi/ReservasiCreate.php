<?php

namespace App\Livewire\Reservasi;

use App\Models\Kamar;
use App\Models\Pesanan;
use App\Models\Tamu;
use Livewire\Component;
use App\Models\Reservasi;


class ReservasiCreate extends Component
{
    

    public $alamat;
    public $kota;
    public $email;
    public $no_identitas;
    public $jumlah_anak;
    public $jumlah_dewasa;
    public $hargaPerMalam = 0;
    
    public $tanggal_check_in;
    public $tanggal_check_out;
    public $id_jenis_kamar;
    public $jumlahKamar = 1;
    public $jumlahTersedia = 0;
    public $idTamu = null;
    public $nama;
    public $no_tlpn;
    public $isSuccess = false;


    public $id_diskon;
    public $persentase_diskon;


    public $id_harga;
    public $persentase_kenaikan_harga;

    
    public $jumlahHari = 0;
    public $hargaDasar = 0;
    public $hargaNormal;
    public $totalHarga = 0;

    public $reset = false;



    private function buatNoReservasi()
    {
        do {
            $lastReservasi = Reservasi::latest('id')->first();
            $nextNumber = $lastReservasi ? ((int)substr($lastReservasi->no_reservasi, 4)) + 1 : 1;
            $no_reservasi = 'RESV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } while (Reservasi::where('no_reservasi', $no_reservasi)->exists());

        return $no_reservasi;
    }



    public function resetForm() {
        $this->alamat = null;
        $this->kota = null;
        $this->email = null;
        $this->no_identitas = null;
        $this->jumlah_anak = null;
        $this->jumlah_dewasa = null;
        
        $this->tanggal_check_out = null;
        $this->tanggal_check_in = null;
        $this->id_jenis_kamar = null;
        $this->jumlahKamar = 1;
        $this->jumlahTersedia = 0;
        $this->hargaPerMalam = 0;

        $this->idTamu = null;
        $this->nama = null;
        $this->no_tlpn = null;
    
        $this->id_diskon = null;
        $this->persentase_diskon = null;
    
        $this->id_harga = null;
        $this->persentase_kenaikan_harga = null;
    
        $this->jumlahHari = 0;
        $this->hargaDasar = 0;
        $this->totalHarga = 0;
    
        $this->reset = true; // Menandakan bahwa reset telah dilakukan
        $this->isSuccess = false;
        return;
    }
    




    public function save()
    {
        // dd($this->idTamu);
        if(is_null($this->idTamu) || $this->idTamu == ""){

            if (is_null($this->nama) || is_null($this->no_tlpn)) {
                $this->dispatch('notify', title: 'fail', message: 'Data tamu wajib diisi !');
                return;
            }

        }

        $hargaPerMalam = number_format($this->hargaPerMalam, 2, '.', '');
        $totalHarga = number_format($this->totalHarga, 2, '.', '');
        // Menampilkan semua properti untuk debug
        // dd([
        //     'tanggal_check_in' => $this->tanggal_check_in,
        //     'tanggal_check_out' => $this->tanggal_check_out,
        //     'id_jenis_kamar' => $this->id_jenis_kamar,
        //     'jumlahKamar' => $this->jumlahKamar,
        //     'jumlahTersedia' => $this->jumlahTersedia,
        //     'idTamu' => $this->idTamu,
        //     'nama' => $this->nama,
        //     'no_tlpn' => $this->no_tlpn,
        //     'jumlahHari' => $this->jumlahHari,
        //     'hargaPerMalam' => $hargaPerMalam,
        //     'hargaNormal' => floatval($this->hargaNormal),
        //     'totalHarga' => $totalHarga,
        //     'reset' => $this->reset,
        //     'id_diskon' => $this->id_diskon,
        //     'persentase_diskon' => $this->persentase_diskon,
        //     'id_harga' => $this->id_harga,
        //     'persentase_kenaikan_harga' => $this->persentase_kenaikan_harga,
        // ]);

        // dd(is_null($this->idTamu));

   
        if (is_null($this->idTamu)) {
            $tamu = Tamu::create([
                'nama' => $this->nama,
                'no_tlpn' => $this->no_tlpn,
                'alamat' => 'no-data',
                'email' => 'no-data', 
                'kota' => 'no-data',
                'no_identitas' => 'no-data',
            ]);
        }

        $kamar = Kamar::where('id_jenis_kamar', $this->id_jenis_kamar)->first();


        $reservasi = Reservasi::create([
            'no_reservasi' => $this->buatNoReservasi(),
            'id_tamu' => $tamu->id ?? $this->idTamu,
            'tanggal_check_in' => $this->tanggal_check_in,
            'tanggal_check_out' => $this->tanggal_check_out,
            'jumlah_kamar' => $this->jumlahKamar,
            'total_harga' => $totalHarga,
            'status_reservasi' => 'dipesan',
        ]);




        Pesanan::create([
            'id_reservasi' => $reservasi->id,
            'id_kamar' => $kamar->id, 
            'id_diskon' => $this->id_diskon ?? null,
            'id_harga' => $this->id_harga ?? null,
            'harga_kamar' => $kamar->harga_kamar,
            'harga_akhir' => floatval($hargaPerMalam),
            'jumlah_malam' => $this->jumlahHari,
            'subtotal' => floatval($hargaPerMalam) * $this->jumlahHari
        ]);

        // $reservasi->update([
        //     'total_harga' => Pesanan::where('id_reservasi', $reservasi->id)->sum('subtotal')
        // ]);

        $simpan = $reservasi;

        // dd($simpan);
        is_null($simpan)
            ? $this->dispatch('notify', title: 'fail', message: 'Data gagal disimpan')
            : $this->dispatch('notify', title: 'success', message: 'Data berhasil disimpan');
    
        $this->resetForm();
    }
    
    public function render()
    {
        return view('livewire.reservasi.reservasi-create');
    }
}
