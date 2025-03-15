<?php

namespace App\Livewire\DataTamu;

use DateTime;
use Carbon\Carbon;
use App\Models\Tamu;
use App\Models\Riwayat;
use Livewire\Component;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\TamuForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TamuTable extends Component
{
    use WithPagination, WithSorting;

    public TamuForm $form;


    public $showModalCheckIn = false;
    public $showModalCheckOut = false;
    public $showModalSelesai = false;
    public $showModalBatal = false;
    public $showModalInvoice = false;

    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'tamu.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default


    public $hargaDasar = 0;
    public $hargaAkhir = 0;
    public $hargaKhusus = null;
    public $persentase_kenaikan_harga = 0;

    public $diskon = null;
    public $persentase = 0;
    public $hargaDiskon = 0;
    public $denda = 0;

    public $totalHarga = 0;
    public $jumlahHari = 0;
    public $kembalian = 0;
    public $jumlahPembayaran = 0;
    public $hargaNormal = 0;
    public $idTamu;
    public $idRes;
    public $no_kamar;
    public $tipe_kamar;
    public $jenis_ranjang;
    public $no_reservasi;
    public $tanggal_check_in;
    public $tanggal_check_out;
    // public $idPembayaran;


        public $namaTamu, $alamatTamu, $kotaTamu, $emailTamu, $teleponTamu;
        public $reservasi = [];
        public $invoiceNumber, $invoiceDate;



        public function invoiceCheckIn($idTamu)
        {
            $tamu = Tamu::with([
                'reservasi' => function ($query) {
                    $query->with(['kamar.jenisKamar', 'pembayaran','diskon','harga'])
                        ->whereIn('status_reservasi', ['check_in']);
                }
            ])->find($idTamu);

                // dd($tamu);
            if (!$tamu || in_array('no-data', [$tamu->kota, $tamu->alamat, $tamu->no_identitas, $tamu->email])) {
                $this->dispatch('notify', title: 'fail', message: 'Lengkapi data Tamu terlebih dahulu');
                $this->showModalInvoice = false;
                return;
            }

            if ($tamu) {
                // Set data tamu ke variabel terpisah
                $this->idTamu = $tamu->id;
                $this->namaTamu = $tamu->nama;
                $this->alamatTamu = $tamu->alamat;
                $this->kotaTamu = $tamu->kota;
                $this->emailTamu = $tamu->email;
                $this->teleponTamu = $tamu->no_tlpn;

                // dd($this->idTamu);

                // Set reservasi ke array
                $this->reservasi = $tamu->reservasi->map(function ($item) {
                    $persentase_kenaikan_harga = $item->harga ? $this->toDecimal($item->harga->persentase_kenaikan_harga) : 0;
                    $persentase = $item->diskon ? $this->toDecimal($item->diskon->persentase) : 0;
                    
                    $hargaDasar = $item->kamar->harga_kamar;
                    
                    $hargaKhusus = $item->harga ? $hargaDasar * (1 + ($persentase_kenaikan_harga / 100)) : $hargaDasar;
           
                    $hargaAkhir = $item->harga ? $hargaKhusus * (1 - ($persentase / 100)) : ($item->diskon ? $hargaDasar * (1 - ($persentase / 100)) : $hargaDasar);

                    return [
                        'tipe_kamar' => $item->kamar->jenisKamar->tipe_kamar,
                        'jenis_ranjang' => $item->kamar->jenisKamar->jenis_ranjang,
                        'no_kamar' => $item->kamar->no_kamar,
                        'diskon' => $item->diskon ? $item->diskon->persentase : 0,
                        'harga' => $item->harga ? $item->harga->persentase_kenaikan_harga : 0,
                        'tanggal_check_out' => $item->tanggal_check_out,
                        'durasi' => \Carbon\Carbon::parse($item->tanggal_check_in)->diffInDays($item->tanggal_check_out),
                        'harga_kamar' => $hargaAkhir,
                        'total_harga' => $item->total_harga,
                        'persen_diskon' => $persentase,
                        'persen_harga' => $persentase_kenaikan_harga,
                        'status' => ucfirst($item->status_reservasi)
                    ];
                })->toArray();

                // dd($this->reservasi);

                // Set informasi invoice
                $this->invoiceNumber = 'INV' . str_pad($idTamu, 5, '0', STR_PAD_LEFT);
                $this->invoiceDate = now()->format('d/m/Y');
                $this->totalHarga = collect($this->reservasi)->sum('total_harga');
                $this->showModalInvoice = true;
            }
        }



        public function invoiceCheckOut($idTamu)
        {
            $tamu = Tamu::with([
                'reservasi' => function ($query) {
                    $query->with(['kamar.jenisKamar', 'pembayaran'])
                        ->whereIn('status_reservasi', ['check_out']);
                }
            ])->find($idTamu);

                // dd($tamu);
            if (!$tamu || in_array('no-data', [$tamu->kota, $tamu->alamat, $tamu->no_identitas, $tamu->email])) {
                $this->dispatch('notify', title: 'fail', message: 'Lengkapi data Tamu terlebih dahulu');
                $this->showModalInvoice = false;
                return;
            }

            if ($tamu) {
                // Set data tamu ke variabel terpisah
                $this->idTamu = $tamu->id;
                $this->namaTamu = $tamu->nama;
                $this->alamatTamu = $tamu->alamat;
                $this->kotaTamu = $tamu->kota;
                $this->emailTamu = $tamu->email;
                $this->teleponTamu = $tamu->no_tlpn;

                // dd($this->idTamu);

                // Set reservasi ke array
                $this->reservasi = $tamu->reservasi->map(function ($item) {
                    return [
                        'tipe_kamar' => $item->kamar->jenisKamar->tipe_kamar,
                        'jenis_ranjang' => $item->kamar->jenisKamar->jenis_ranjang,
                        'no_kamar' => $item->kamar->no_kamar,
                        'tanggal_check_in' => $item->tanggal_check_in,
                        'tanggal_check_out' => $item->tanggal_check_out,
                        'durasi' => \Carbon\Carbon::parse($item->tanggal_check_in)->diffInDays($item->tanggal_check_out),
                        'harga_kamar' => $item->kamar->harga_kamar,
                        'total_harga' => $item->total_harga,
                        'status' => ucfirst($item->status_reservasi)
                    ];
                })->toArray();

                // Set informasi invoice
                $this->invoiceNumber = 'INV' . str_pad($idTamu, 5, '0', STR_PAD_LEFT);
                $this->invoiceDate = now()->format('d/m/Y');
                $this->totalHarga = collect($this->reservasi)->sum('total_harga');
                $this->showModalInvoice = true;
            }
        }






    public function cetakInvoice() {
        return Redirect()->route('resep.invoice',['idTamu' => $this->idTamu]);
    }









    // Hitung jumlah hari antara check-in dan check-out
    public function hitungJumlahHari($tanggal_check_in, $tanggal_check_out)
    {
        $checkIn = new DateTime($tanggal_check_in);
        $checkOut = new DateTime($tanggal_check_out);
        $interval = $checkIn->diff($checkOut);
        return $interval->days;
    }

    // Konversi angka ke format desimal
    public function toDecimal($harga)
    {
        return number_format((float) $harga, 2, '.', '');
    }




    public function checkIn($idTamu, $idRes)
    {
        $this->idTamu = $idTamu;
        $this->idRes = $idRes;
        $tamu = Tamu::find($idTamu);

        // Validasi kelengkapan data tamu
        if (!$tamu || in_array('no-data', [$tamu->kota, $tamu->alamat, $tamu->no_identitas, $tamu->email])) {
            $this->dispatch('notify', title: 'fail', message: 'Lengkapi data Tamu terlebih dahulu');
            $this->showModalCheckIn = false;
            return;
        }

        // Ambil data reservasi dengan relasi
        $resev = Reservasi::with('harga', 'diskon', 'kamar.jenisKamar')->find($idRes);

        if (!$resev) {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
            return; 
        }

        // dd($resev);
        $this->tanggal_check_in = $resev->tanggal_check_in;
        $this->tanggal_check_out = $resev->tanggal_check_out;
        $this->no_kamar = $resev->kamar->no_kamar;
        $this->tipe_kamar = $resev->kamar->jenisKamar->tipe_kamar;
        $this->jenis_ranjang  = $resev->kamar->jenisKamar->jenis_ranjang;
        $this->no_reservasi = $resev->no_reservasi;
        // Ambil persentase kenaikan harga dan diskon (jika ada)
        $this->persentase_kenaikan_harga = $resev->harga ? $this->toDecimal($resev->harga->persentase_kenaikan_harga) : 0;
        $this->persentase = $resev->diskon ? $this->toDecimal($resev->diskon->persentase) : 0;
        $this->hargaKhusus = $resev->harga ?? null;
        $this->diskon = $resev->diskon ?? null;
        // Set harga dasar dan harga akhir
        $this->hargaDasar = $this->toDecimal($resev->kamar->harga_kamar ?? 0);
        // $this->hargaAkhir = $this->toDecimal($this->hargaDasar * ($this->persentase_kenaikan_harga / 100));
        $this->hargaAkhir = $this->hargaKhusus ? $this->hargaDasar * (1 + $this->persentase_kenaikan_harga / 100) : $this->hargaDasar;
        $this->hargaDiskon = $this->hargaKhusus ? $this->hargaAkhir * (1 - ($this->persentase / 100)) : $this->hargaDasar  * (1 - ($this->persentase / 100));
 

        // Hitung jumlah hari
        $this->jumlahHari = $this->hitungJumlahHari($resev->tanggal_check_in, $resev->tanggal_check_out) ?? 0;

        
        // Hitung total harga
        // $thi = ($this->hargaDasar + $this->hargaAkhir) * $this->jumlahHari;
        $this->hargaNormal = $this->hargaDasar * $this->jumlahHari;
        // $this->totalHarga = $this->persentase > 0
        //     ? $this->toDecimal($hargaNormal - ($hargaNormal * ($this->persentase / 100)))
        //     : $this->toDecimal($hargaNormal);
        $this->totalHarga = $this->ToDecimal($resev->total_harga);
        $this->showModalCheckIn = true;

        // dd(
        //    [
        //      $this->hargaDasar,
        //      $this->hargaAkhir,
        //      $this->hargaKhusus,
        //      $this->persentase_kenaikan_harga,
        //      $this->diskon,
        //      $this->persentase,
        //      $this->totalHarga,
        //      $this->jumlahHari,
        //      $this->kembalian,
        //      $this->jumlahPembayaran,
        //      $this->hargaNormal,

        //    ]
        // );
    }


    public function checkOut($idTamu, $idRes)
    {
        $this->idTamu = $idTamu;
        $this->idRes = $idRes;
        $tamu = Tamu::find($idTamu);

        // Validasi kelengkapan data tamu
        if (!$tamu || in_array('no-data', [$tamu->kota, $tamu->alamat, $tamu->no_identitas, $tamu->email])) {
            $this->dispatch('notify', title: 'fail', message: 'Lengkapi data Tamu terlebih dahulu');
            $this->showModalCheckOut = false;
            return;
        }

        // Ambil data reservasi dengan relasi
        $resev = Reservasi::with('harga', 'diskon', 'kamar.jenisKamar')->find($idRes);

        if (!$resev) {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
            return;
        }
        $this->tanggal_check_in = $resev->tanggal_check_in;
        $this->tanggal_check_out = $resev->tanggal_check_out;
        $this->no_kamar = $resev->kamar->no_kamar;
        $this->tipe_kamar = $resev->kamar->jenisKamar->tipe_kamar;
        $this->jenis_ranjang  = $resev->kamar->jenisKamar->jenis_ranjang;

        // Ambil persentase kenaikan harga dan diskon (jika ada)
        $this->persentase_kenaikan_harga = $resev->harga ? $this->toDecimal($resev->harga->persentase_kenaikan_harga) : 0;
        $this->persentase = $resev->diskon ? $this->toDecimal($resev->diskon->persentase) : 0;
        $this->no_reservasi = $resev->no_reservasi;

        // Set harga dasar dan harga akhir
        $this->hargaDasar = $this->toDecimal($resev->kamar->harga_kamar ?? 0);
        $this->hargaAkhir = $this->toDecimal($this->hargaDasar * ($this->persentase_kenaikan_harga / 100));

        $this->hargaKhusus = $resev->harga ?? null;
        $this->diskon = $resev->diskon ?? null;

        // Hitung jumlah hari
        $this->jumlahHari = $this->hitungJumlahHari($resev->tanggal_check_in, $resev->tanggal_check_out) ?? 0;

        
        // Hitung total harga
        // $thi = ($this->hargaDasar + $this->hargaAkhir) * $this->jumlahHari;
        $normal = $this->hargaDasar * $this->jumlahHari;
        $this->hargaNormal = (float) $this->toDecimal($normal);
        // $this->totalHarga = $this->persentase > 0
        //     ? $this->toDecimal($hargaNormal - ($hargaNormal * ($this->persentase / 100)))
        //     : $this->toDecimal($hargaNormal);
        $this->totalHarga = $this->toDecimal($resev->total_harga);
        $this->showModalCheckOut = true;

        // $this->totalHarga = $this->toDecimal($this->totalHarga);
        // dd(
        //    [
        //      $this->hargaDasar,
        //      $this->hargaAkhir,
        //      $this->hargaKhusus,
        //      $this->persentase_kenaikan_harga,
        //      $this->diskon,
        //      $this->persentase,
        //      $this->totalHarga,
        //      $this->jumlahHari,
        //      $this->kembalian,
        //      $this->jumlahPembayaran,
        //      $this->hargaNormal,
        //    ]
        // );
    }





    public function batal($idRes,$idTamu){


        $batal = Reservasi::where('id',$idRes)->where('id_tamu',$idTamu)->first();
        // dd($batal);
        if ($batal) {
            $this->idRes = $idRes;
            $this->idTamu = $idTamu;
            $this->no_reservasi = $batal->no_reservasi;
        }else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalBatal = true;
    }

    public function submitBatal(){

        $batal = Reservasi::where('id',$this->idRes)->where('id_tamu',$this->idTamu)->first();

        // dd($batal);
   
        $batal->update([
            'status_reservasi' => 'batal'
        ]);
        
        is_null($batal)
        ? $this->dispatch('notify', title: 'fail', message: 'Pembatalan reservasi gagal dilakukan')
        : $this->dispatch('notify', title: 'success', message: 'Pembatalan reservasi berhasil dilakukan');
        $this->showModalBatal = false;

    }


    public function selesai($idRes,$idTamu){


        $selesai = Reservasi::where('id',$idRes)->where('id_tamu',$idTamu)->first();
        // dd($selesai);
        if ($selesai) {
            $this->idRes = $idRes;
            $this->idTamu = $idTamu;
            $this->no_reservasi = $selesai->no_reservasi;
        }else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalSelesai = true;
    }
    


    public function submitSelesai()
    {
        $selesai = Reservasi::where('id', $this->idRes)
            ->where('id_tamu', $this->idTamu)
            ->with(['pembayaran', 'diskon', 'harga', 'kamar.jenisKamar', 'pembayaran.user'])
            ->first();
    
        if (!$selesai) {
            $this->dispatch('notify', title: 'fail', message: 'Penyelesaian reservasi gagal dilakukan');
            return;
        }
    
        // Hitung jumlah hari
        $jumlahHari = Carbon::parse($selesai->tanggal_check_in)->diffInDays($selesai->tanggal_check_out);
    
        // Simpan ke tabel `riwayat`
        Riwayat::create([
            'id_reservasi' => $selesai->id,
            'id_pembayaran' => $selesai->pembayaran->id ?? null,
            'no_reservasi' => $selesai->no_reservasi,
            'nama_tamu' => $selesai->tamu->nama,
            'persentase_diskon' => $selesai->diskon->persentase ?? null,
            'persentase_kenaikan_harga' => $selesai->harga->persentase_kenaikan_harga ?? null,
            'kamar' => $selesai->kamar->jenisKamar->tipe_kamar . ' - ' . $selesai->kamar->jenisKamar->jenis_ranjang,
            'jumlah_pembayaran' => $selesai->pembayaran->jumlah_pembayaran ?? null,
            'kembalian' => $selesai->pembayaran->kembalian ?? null,
            'resepsionis' => $selesai->pembayaran->user->username ?? null,
            'tanggal_check_in' => $selesai->tanggal_check_in,
            'tanggal_check_out' => $selesai->tanggal_check_out,
            'jumlah_hari' => $jumlahHari,
            'total_harga' => $selesai->total_harga,
            'denda' => $selesai->denda ?? null,
            'status_reservasi' => 'selesai',
            'keterangan' => $selesai->keterangan ?? null,
        ]);
    
        // Update status reservasi menjadi selesai
        $selesai->update([
            'status_reservasi' => 'selesai'
        ]);
    
        $this->dispatch('notify', title: 'success', message: 'Penyelesaian reservasi berhasil dilakukan');
        $this->showModalSelesai = false;
    }
    




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



    public function pembayaran()
    {

        $jumlahPembayaran = $this->converDescimal($this->jumlahPembayaran);
        // dd($jumlahPembayaran);


        // $checkJumlahPembayaran = $jumlahPembayaran 

        if ($jumlahPembayaran == 0.00) {
            $this->dispatch('notify', title: 'fail', message: 'Jumlah Pembayaran Tidak Boleh Nol!');
            return;
        }elseif ($jumlahPembayaran < $this->totalHarga ) {
            $this->dispatch('notify', title: 'fail', message: 'Jumlah Pembayaran yang dimasukan Kurang tidak boleh kurang ! ');
            return;
        }

        // Hitung kembalian
        $kembalian = max(0, $this->toDecimal($jumlahPembayaran) - $this->totalHarga);
        
        $this->kembalian = number_format((float) $kembalian, 2, '.', '');
        // dd($this->kembalian);

        $auth = Auth::id();
      

        $pembayaran = Pembayaran::create([
            'id_reservasi' => $this->idRes,
            'id_user' => $auth,
            'jumlah_pembayaran' => $jumlahPembayaran,
            'kembalian' => $this->kembalian
        ]);

        if ($pembayaran) {

            $res = Reservasi::find($this->idRes);
            $res->update([
                'status_reservasi' => 'check_in'
            ]);

            is_null($pembayaran)
            ? $this->dispatch('notify', title: 'fail', message: 'Pembayaran gagal dilakukan')
            : $this->dispatch('notify', title: 'success', message: 'Pembayaran berhasil dilakukan');
            $this->showModalCheckIn = false;
        
        }else{
            $this->dispatch('notify', title: 'fail', message: 'Pembayaran gagal!');
            $this->showModalCheckIn = false;
        }
        
    }



    public function submitCheckOut(){

        $denda = (float) $this->converDescimal($this->denda);
        
        $checkOut = Reservasi::find($this->idRes);

        $checkOut->update([
            'denda' => $denda == 0.0 ? 0.00 : $denda,
            'status_reservasi' => 'check_out'
        ]);
        
        is_null($checkOut)
        ? $this->dispatch('notify', title: 'fail', message: 'Check out gagal dilakukan')
        : $this->dispatch('notify', title: 'success', message: 'Check out berhasil dilakukan');
        $this->showModalCheckOut = false;

    }



    // Realtime proses
    #[On('dispatch-tamu-create-save')]
    #[On('dispatch-tamu-update-edit')]
    #[On('dispatch-tamu-delete-del')]
    public function render()
    {
        return view('livewire..data-tamu.tamu-table',
        [
            'data' =>  Tamu::with(['reservasi.pesanan.kamar.jenisKamar','reservasi.pembayaran'])
            ->withCount('reservasi')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->paginate),
        ]
    );
    }
}
