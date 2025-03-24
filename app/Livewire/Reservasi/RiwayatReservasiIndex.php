<?php

namespace App\Livewire\Reservasi;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Reservasi;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class RiwayatReservasiIndex extends Component
{
    use WithPagination, WithSorting;


    public $paginate = 5; // Jumlah data per halaman
    public $sortDirection = 'desc'; // Arah pengurutan default
    public $sortBy = 'reservasi.id'; // Kolom default untuk pengurutan



    public $id;
    public $no_reservasi;
    public $namaTamu;
    public $persentase_diskon;
    public $persentase_kenaikan_harga;
    public $kamar;
    public $jumlahPembayaran;
    public $kembalian;
    public $user;
    public $denda;
    public $Idenda;
    public $keterangan;
    public $Iketerangan;
    public $jumlahHari;
    public $jamCheckIn;
    public $jamCheckOut;


    public $idTamu;
    public $alamatTamu;
    public $kotaTamu;
    public $emailTamu;
    public $teleponTamu;


    public $tipe_kamar;
    public $jenis_ranjang;
    public $no_kamar;
    public $tanggal_check_in;
    public $tanggal_check_out;
    public $durasi;
    public $harga_kamar;
    public $total_harga;
    public $status_reservasi;


    public $invoiceNumber;
    public $invoiceDate;



    public $showModalDetail = false;
    public $showModalDelete = false;
    public $showModalInvoice = false;
    public $showModalBatal = false;
    public $showModalCheckIn = false;
    public $showModalCheckOut = false;
    public $showModalSelesai = false;


    public $pembayaran = false;
    public $jumlahKamar;
    public $jenisKamar;
    public $noTlpn;
    public $harga_akhir;
    public $jumlah_malam;
    public $subtotal;
    public $pesanan = [];
    public $hargaDasarList = [];
    public $hargaAkhirList = [];
    public $invoice = [];






    public function converDescimal($harga)
    {
        // Hapus "Rp " jika ada di depan string
        $harga = str_replace("Rp ", "", $harga);
    
        // Hapus titik sebagai pemisah ribuan
        $harga = str_replace(".", "", $harga);
    
        // Ganti koma dengan titik untuk desimal
        $harga = str_replace(",", ".", $harga);
    
        // Konversi ke format decimal (float)
        return number_format((float) $harga, 2, '.', '');
    }


    public function toDecimal($harga)
    {
        return number_format((float) $harga, 2, '.', '');
    }




    public function detail($id) 
    {
        Carbon::setLocale('id');

        $this->id = $id;

        $resev = Reservasi::where('id', $id)
            ->with('pesanan.diskon', 'pesanan.harga', 'tamu', 'pesanan.kamar.jenisKamar', 'pembayaran.user')
            ->first();


            // dd($resev);
            // dd($pesanan);
        if ($resev) {

            $this->no_reservasi = $resev->no_reservasi;
            $this->namaTamu = $resev->tamu->nama;
            $this->noTlpn = $resev->tamu->no_tlpn;
            $this->tanggal_check_in = $resev->tanggal_check_in;
            $this->tanggal_check_out = $resev->tanggal_check_out;
            $this->jumlahKamar = $resev->jumlah_kamar;
            $this->total_harga = $resev->total_harga;
            $this->denda = $resev->denda;

            if ($resev->status_reservasi == 'check_in') {
                $this->status_reservasi = 'Check in';
            }elseif ($resev->status_reservasi == 'check_out') {
                $this->status_reservasi = 'Check out';
            }else{
            $this->status_reservasi = $resev->status_reservasi;
            }


            $this->keterangan = $resev->keterangan;
            $this->jumlahHari = \Carbon\Carbon::parse($resev->tanggal_check_in)->diffInDays($resev->tanggal_check_out);


            // Mapping data pesanan ke array
            $pesanan = $resev->pesanan->map(function ($item) {
                return [
                    'no_kamar' => $item['kamar']['no_kamar'], 
                    'jenisKamar' => $item['kamar']['jenisKamar']['tipe_kamar'] . ' - ' . $item['kamar']['jenisKamar']['jenis_ranjang'],
                    'harga_kamar' => $item['harga_kamar'],
                    'harga_akhir' => $item['harga_akhir'],
                    'jumlah_malam' => $item['jumlah_malam'],
                    'persentase_diskon' =>  isset($item['diskon']) ? $item['diskon']['persentase'] : 0,
                    'persentase_kenaikan_harga' => isset($item['harga']) ? $item['harga']['persentase_kenaikan_harga'] : 0,
                    'subtotal' => $item['subtotal']
                ];
            });
            
            $this->pesanan = $pesanan;
            


            $this->hargaDasarList = $pesanan->mapWithKeys(function ($item) {
                return [$item['no_kamar'] => $item['harga_akhir'] ]; // Akses no_kamar dan harga_akhir sebagai array key
            });
            
            $this->hargaAkhirList = $pesanan->mapWithKeys(function ($item) {
                return [$item['no_kamar'] => floatval( $item['harga_akhir']) * $this->jumlahHari ]; // Akses no_kamar dan harga_akhir sebagai array key
            });
            
            // dd($hargaAkhirList,$hargaDasarList);



            $this->pembayaran = $resev->pembayaran ? true : false;
            $this->jumlahPembayaran = $resev->pembayaran ? $resev->pembayaran->jumlah_pembayaran : null;
            $this->kembalian = $resev->pembayaran ? $resev->pembayaran->kembalian : null;
            $this->user = $resev->pembayaran ? $resev->pembayaran->user->username : null;
            $this->jamCheckIn = $resev->pembayaran ? $resev->pembayaran->created_at->format('H:i:s') : null;
            $this->jamCheckOut = $resev->status_reservasi == 'check_out' ? $resev->updated_at->format('H:i:s') : null;

            // dd($this->user);

            
        } else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalDetail = true;
    }




    public function INVOICE($id) 
    {
        Carbon::setLocale('id');

        $this->id = $id;

        $resev = Reservasi::where('id', $id)
            ->with('pesanan.diskon', 'pesanan.harga', 'tamu', 'pesanan.kamar.jenisKamar', 'pembayaran.user')
            ->first();

            // dd($resev);

        if (!$resev->tamu || in_array('no-data', [$resev->tamu->kota, $resev->tamu->alamat, $resev->tamu->no_identitas, $resev->tamu->email])) {
            $this->dispatch('notify', title: 'fail', message: 'Data Tamu Belum Lengkap!');
            $this->showModalInvoice = false;
            return;
        }

     
        if ($resev) {

            $this->idTamu = $resev->tamu->id;
            $this->namaTamu = $resev->tamu->nama;
            $this->alamatTamu = $resev->tamu->alamat;
            $this->kotaTamu = $resev->tamu->kota;
            $this->emailTamu = $resev->tamu->email;
            $this->teleponTamu = $resev->tamu->no_tlpn;
            $this->invoiceNumber = 'INV' . str_pad($resev->tamu->id, 3, '0', STR_PAD_LEFT) . date('Ymd', strtotime($resev->tanggal_check_in));

            $this->invoiceDate = now()->format('Y-m-d');




            
            $this->jumlahKamar = $resev->jumlah_kamar;
            $this->total_harga = $resev->total_harga;

            $this->denda = $resev->denda;
            if ($resev->status_reservasi == 'check_in') {
                    $this->status_reservasi = 'Check in';
            }elseif ($resev->status_reservasi == 'check_out') {
                    $this->status_reservasi = 'Check out';
            }else{
                $this->status_reservasi = $resev->status_reservasi;
            }

            if ($resev->status_reservasi == 'check_in') {

                $this->jumlahPembayaran = $resev->pembayaran->jumlah_pembayaran;
                $this->kembalian = $resev->pembayaran->kembalian;
                $this->user = $resev->pembayaran->user->username;

            }elseif ($resev->status_reservasi == 'check_out') {
                    $this->jumlahPembayaran = $resev->pembayaran->jumlah_pembayaran;
                    $this->kembalian = $resev->pembayaran->kembalian;
                    $this->user = $resev->pembayaran->user->username;
                    $this->denda = $resev->denda;
            }


            

            $this->keterangan = $resev->keterangan;
            $this->jumlahHari = \Carbon\Carbon::parse($resev->tanggal_check_in)->diffInDays($resev->tanggal_check_out);
            
            // dd($this->total_harga);

            // Mapping data pesanan ke array
            $pesanan = $resev->pesanan->map(function ($item) {
                return [
                    'jenisKamar' => $item['kamar']['jenisKamar']['tipe_kamar'] . ' - ' . $item['kamar']['jenisKamar']['jenis_ranjang'],
                    'harga_kamar' => $item['harga_kamar'],
                    'harga_akhir' => $item['harga_akhir'],
                    'jumlah_malam' => $item['jumlah_malam'],
                    'subtotal' => $item['subtotal']
                ];
            });
            
            $this->invoice = $pesanan;
            

        } else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalInvoice = true;

    }
    
    public function cetakInvoice() {
        return Redirect()->route('resep.invoice',['idRes' => $this->id]);
    }


    public function delete($id) {
        $this->id = $id;

        $resev = Reservasi::where('id',$id)->first();
        // dd($resev);
        if ($resev) {
            $this->no_reservasi = $resev->no_reservasi;
        }else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalDelete = true;
    }



    public function submitDelete(){
        $delete = Reservasi::destroy($this->id);
        is_null($delete)
        ? $this->dispatch('notify', title: 'fail', message: 'Hapus reservasi gagal dilakukan')
        : $this->dispatch('notify', title: 'success', message: 'Hapus reservasi berhasil dilakukan');
        $this->showModalDelete = false;
    }














    public function render()
    {
        return view('livewire.reservasi.riwayat-reservasi-index', [
            'data' => Reservasi::with('pesanan.kamar','tamu','pesanan.diskon','pesanan.harga','pembayaran')
            ->whereIn('status_reservasi', ['selesai', 'batal']) // Menambahkan filter status_reservasi
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->paginate),
        ]);
    }
}
