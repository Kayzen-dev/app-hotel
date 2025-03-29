<?php

namespace App\Livewire\Reservasi;

use Carbon\Carbon;
use App\Models\Kamar;
use App\Models\Pesanan;
use Livewire\Component;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservasiIndex extends Component
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
    public $IjumlahPembayaran;
    public $kembalian;
    public $user;
    public $denda;
    public $Idenda;
    public $keterangan;
    public $Iketerangan;
    public $jumlahHari;
    public $bayarDenda = false;
    public $bayarKedua;
    public $jamCheckIn;
    public $jamCheckOut;
    public $nomorKamar = [];

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

            // dd($this->denda);

            if ($resev->status_reservasi == 'check_in') {
                $this->status_reservasi = 'Check in';
            }elseif ($resev->status_reservasi == 'check_out') {
                $this->status_reservasi = 'Check out';
            }else{
            $this->status_reservasi = $resev->status_reservasi;
            }


            $this->keterangan = $resev->keterangan;
            $this->jumlahHari = \Carbon\Carbon::parse($resev->tanggal_check_in)->diffInDays($resev->tanggal_check_out);


            $pesanan = $resev->pesanan->map(function ($item) {
                    return [
                'id_pesanan' => $item['id'],
                        'no_kamar' => $item['kamar']['no_kamar'], 
                        'id_jenis_kamar' => $item['kamar']['id_jenis_kamar'],
                        'jenisKamar' => $item['kamar']['jenisKamar']['tipe_kamar'] . ' - ' . $item['kamar']['jenisKamar']['jenis_ranjang'],
                        'harga_kamar' => $item['harga_kamar'],
                        'harga_akhir' => $item['harga_akhir'],
                        'jumlah_malam' => $item['jumlah_malam'],
                        'nomor_kamar' => $item['nomor_kamar'],
                        'persentase_diskon' =>  isset($item['diskon']) ? $item['diskon']['persentase'] : 0,
                        'persentase_kenaikan_harga' => isset($item['harga']) ? $item['harga']['persentase_kenaikan_harga'] : 0,
                        'subtotal' => $item['subtotal']
                    ];
                });

                // dd($pesanan);

            $this->pesanan = $pesanan;
            
            $this->nomorKamar = $pesanan->mapWithKeys(function ($item) {
                // Decode JSON untuk mendapatkan array id_kamar
                $kamarIds = json_decode($item['nomor_kamar'], true);
            
                // Pastikan $kamarIds adalah array dan tidak kosong
                if (is_array($kamarIds) && !empty($kamarIds)) {
                    // Ambil semua data kamar yang ID-nya ada dalam $kamarIds
                    $kamar = Kamar::whereIn('id', $kamarIds)->get(['id', 'no_kamar', 'status_no_kamar']);
                } else {
                    // Jika tidak ada ID kamar, inisialisasi $kamar sebagai koleksi kosong
                    $kamar = collect();
                }
            
                // Ambil hanya no_kamar dan status_no_kamar dari setiap kamar
                $result = $kamar->map(function ($k) use ($item) {
                    return [
                'id_pesanan' => $item['id_pesanan'], // Menyimpan id_pesanan

                        'no_kamar' => $k->no_kamar,
                        'status_no_kamar' => $k->status_no_kamar,
                        'status_pemesanan' => $k->status_pemesanan
                    ];
                });
            
                // Menggunakan id_pesanan sebagai kunci
                return $result; // Pastikan id_pesanan ada dalam $item
            });
            
            // dd($hargaAkhirList,$hargaDasarList);

            // dd($this->nomorKamar);



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
            $this->jamCheckIn = $resev->pembayaran ? $resev->pembayaran->created_at->format('H:i') : null;
            $this->jamCheckOut = $resev->status_reservasi == 'check_out' ? $resev->updated_at->format('H:i') : null;
            // dd($this->user);

            // dd($resev->pembayaran);

            
        } else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalDetail = true;
    }

    
        public function tutupCheckIn() {
            $this->showModalCheckIn = false;
            $this->nomorKamar = [];
        }


        public function checkIn($id) 
        {
            Carbon::setLocale('id');
    
            $this->id = $id;
    
            $resev = Reservasi::where('id', $id)
                ->with('pesanan.diskon', 'pesanan.harga', 'tamu', 'pesanan.kamar.jenisKamar', 'pembayaran.user')
                ->first();
    
            if (!$resev->tamu || in_array('no-data', [$resev->tamu->kota, $resev->tamu->alamat, $resev->tamu->no_identitas, $resev->tamu->email])) {
                $this->dispatch('notify', title: 'fail', message: 'Data Tamu Belum Lengkap!');
                $this->showModalCheckIn = false;
                return;
            }

         
            if ($resev) {
    
                $this->no_reservasi = $resev->no_reservasi;
                $this->tanggal_check_in = $resev->tanggal_check_in;
                $this->tanggal_check_out = $resev->tanggal_check_out;
                $this->jumlahKamar = $resev->jumlah_kamar;
                $this->total_harga = $resev->total_harga;
                $this->denda = $resev->denda;

                // $this->nomorKamar = Kamar::where('id_jenis_kamar', $resev->pesanan[])

                if ($resev->status_reservasi == 'check_in') {
                    $this->status_reservasi = 'Check in';
                }elseif ($resev->status_reservasi == 'check_out') {
                    $this->status_reservasi = 'Check out';
                }else{
                $this->status_reservasi = $resev->status_reservasi;
                }
    
                $this->keterangan = $resev->keterangan;
                $this->jumlahHari = \Carbon\Carbon::parse($resev->tanggal_check_in)->diffInDays($resev->tanggal_check_out);
                
                // dd($this->total_harga);
    
                // Mapping data pesanan ke array
                $pesanan1 = $resev->pesanan->map(function ($item) {
                    return [
                        'id_pesanan' => $item['id'],
                        'no_kamar' => $item['kamar']['no_kamar'], 
                        'id_jenis_kamar' => $item['kamar']['id_jenis_kamar'],
                        'jenisKamar' => $item['kamar']['jenisKamar']['tipe_kamar'] . ' - ' . $item['kamar']['jenisKamar']['jenis_ranjang'],
                        'harga_kamar' => $item['harga_kamar'],
                        'harga_akhir' => $item['harga_akhir'],
                        'jumlah_malam' => $item['jumlah_malam'],
                        'nomor_kamar' => $item['nomor_kamar'],
                        'persentase_diskon' =>  isset($item['diskon']) ? $item['diskon']['persentase'] : 0,
                        'persentase_kenaikan_harga' => isset($item['harga']) ? $item['harga']['persentase_kenaikan_harga'] : 0,
                        'subtotal' => $item['subtotal']
                    ];
                });


                $dataReservasi = [
                    'tanggal_check_in' => $resev['tanggal_check_in']
                ];
    
    
                // dd($dataReservasi);
    
                // Mengambil data pesanan dan menggabungkannya dengan data reservasi
                $pesanan = $resev->pesanan->map(function ($item) use ($dataReservasi) {
                    // Pastikan $item dan $item['kamar'] ada dan valid
                    // Ambil nomor kamar yang sudah dipesan
                    $nomorKamarPemesanan = Reservasi::where('tanggal_check_in', $dataReservasi['tanggal_check_in'])
                        ->where('status_reservasi', 'check_in')
                        ->pluck('nomor_kamar_pemesanan')
                        ->toArray();
    
                    // Uraikan setiap string menjadi array dan gabungkan semua nomor
                    $combinedArray = [];
                    foreach ($nomorKamarPemesanan as $i) {
                        // Pastikan i adalah string yang valid sebelum di-decode
                        $decodedi = json_decode($i, true); // Menggunakan true untuk mendapatkan array asosiatif
                        if (is_array($decodedi)) {
                            $combinedArray = array_merge($combinedArray, $decodedi);
                        }
                    }
    
                    // Hapus duplikat
                    $combinedArray = array_unique($combinedArray);
    
                    // Ubah kembali array menjadi string JSON
                    $res = array_values($combinedArray); // Menggunakan array_values untuk menjaga indeks numerik
    
                    // Jika Anda ingin hasilnya dalam format array dengan satu elemen
                    $finalResult = json_encode($res); // Membuat array dengan satu elemen yang berisi string JSON
    
                    return [
                        'id_reservasi' => $item['id_reservasi'],
                        'id_pesanan' => $item['id'],
                        'no_kamar' => $item['kamar']['no_kamar'], 
                        'id_jenis_kamar' => $item['kamar']['id_jenis_kamar'],
                        'jenisKamar' => $item['kamar']['jenisKamar']['tipe_kamar'] . ' - ' . $item['kamar']['jenisKamar']['jenis_ranjang'],
                        'harga_kamar' => $item['harga_kamar'],
                        'harga_akhir' => $item['harga_akhir'],
                        'jumlah_malam' => $item['jumlah_malam'],
                        'nomor_kamar_pemesanan' => $finalResult,
                        'nomor_kamar' => $item['nomor_kamar'],
                        'persentase_diskon' =>  isset($item['diskon']) ? $item['diskon']['persentase'] : 0,
                        'persentase_kenaikan_harga' => isset($item['harga']) ? $item['harga']['persentase_kenaikan_harga'] : 0,
                        'subtotal' => $item['subtotal']
                    ];
    
    
                });


                
                $this->pesanan = $pesanan;
                
                // dd($this->pesanan);
                // dd($this->pesanan[]['nomor_kamar']);
    
    
                $this->hargaDasarList = $pesanan->mapWithKeys(function ($item) {
                    return [$item['no_kamar'] => $item['harga_akhir'] ]; // Akses no_kamar dan harga_akhir sebagai array key
                });
                
                $this->hargaAkhirList = $pesanan->mapWithKeys(function ($item) {
                    return [$item['no_kamar'] => floatval( $item['harga_akhir']) * $this->jumlahHari ]; // Akses no_kamar dan harga_akhir sebagai array key
                });


                $this->nomorKamar = $pesanan->mapWithKeys(function ($item) {


                    // Decode JSON untuk mendapatkan array id_kamar
                    $kamarIds = json_decode($item['nomor_kamar_pemesanan'], true);
                    $kamarId1 = json_decode($item['nomor_kamar'], true);
                
                    // Ambil semua data kamar
                    // $kamar = Kamar::all(['id', 'no_kamar', 'status_no_kamar','status_pemesanan']);
                    $kamar = Kamar::where('id_jenis_kamar', $item['id_jenis_kamar'])
              ->get(['id', 'no_kamar', 'status_no_kamar', 'status_pemesanan']);
                
                    // Modifikasi status_no_kamar menjadi true jika id kamar ada dalam id_kamar
                    $kamarData = $kamar->map(function ($kamar) use ($kamarIds,$kamarId1) {
                        // Jika id kamar ada dalam array id_kamar, ubah status_no_kamar menjadi true
                        if (in_array($kamar->id, $kamarIds)) {
                            $kamar->status_pemesanan = 1; // Ubah status_no_kamar menjadi true
                        }

                        if (in_array($kamar->id, $kamarId1)) {
                            $kamar->status_no_kamar = 1; // Ubah status_no_kamar menjadi true
                        }


                        return $kamar;
                    });
                
                    // Ambil hanya no_kamar dan status_no_kamar
                    $result = $kamarData->map(function ($kamar) use ($item) {
                        return [
                            'id_pesanan' => $item['id_pesanan'], // Menyimpan id_pesanan
                            'no_kamar' => $kamar->no_kamar,
                            'status_no_kamar' => $kamar->status_no_kamar,
                            'status_pemesanan' => $kamar->status_pemesanan
                        ];
                    });
                
                    // Menggunakan id_pesanan sebagai kunci
                    return $result;
                });
                
                // dd($hargaAkhirList,$hargaDasarList);

                // dd($this->nomorKamar);
    
    
    
                $this->pembayaran = $resev->pembayaran ? true : false;
                $this->jumlahPembayaran = $resev->pembayaran ? $resev->pembayaran->jumlah_pembayaran : null;
                $this->kembalian = $resev->pembayaran ? $resev->pembayaran->kembalian : null;
                $this->user = $resev->pembayaran ? $resev->pembayaran->user->username : null;
    
                
            } else {
                $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
            }
    
            $this->showModalCheckIn = true;
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


    
    
        public function submitCheckIn(){
            
            $jumlahPembayaran = $this->converDescimal($this->IjumlahPembayaran);
            // dd($jumlahPembayaran);
            // dd($this->id);
            $resev = Reservasi::where('id',$this->id)->with(
                'pesn'
            )->first();

            // dd($jumlahPembayaran);
            // $pesanan = $resev->pesanan->first();

            // dd($resev);
            $kamarData = json_decode($resev->pesn->nomor_kamar, true);

            $jumlahKamarDipesan = count($kamarData);
            $jumlahKamarMax = $resev->jumlah_kamar;
            // dd([
            //     'datakamar' => $resev->pesn->nomor_kamar,
            //     'totalISi' => $jumlahKamarDipesan,
            //     'jumlahMAc' => $jumlahKamarMax,
            //     'test' => $jumlahKamarDipesan != $jumlahKamarMax
            // ]);


            if ($jumlahKamarDipesan != $jumlahKamarMax) {
                # code...
                $this->dispatch('notify', title: 'fail', message: 'Jumlah Kamar Harus Sesuai dengan pesanan !');
                return;
            }

            if ($jumlahPembayaran == 0.00) {
                $this->dispatch('notify', title: 'fail', message: 'Jumlah Pembayaran Tidak Boleh Nol!');
                return;
            }elseif ($jumlahPembayaran < $resev->total_harga ) {
                $this->dispatch('notify', title: 'fail', message: 'Jumlah Pembayaran yang dimasukan Kurang tidak boleh kurang ! ');
                return;
            }


                    // Hitung kembalian
                $kembalian = max(0, $this->toDecimal($jumlahPembayaran) - $this->total_harga);
                
                $this->kembalian = number_format((float) $kembalian, 2, '.', '');
                // dd($this->kembalian);


                $pembayaran = Pembayaran::create([
                    'id_reservasi' => $this->id,
                    'id_user' => Auth::id(),
                    'jumlah_pembayaran' => $jumlahPembayaran,
                    'kembalian' => $this->kembalian
                ]);

                if ($pembayaran && $this->showModalCheckIn) {

                    $res = Reservasi::find($this->id);
                    $res->update([
                        'nomor_kamar_pemesanan' => $resev->pesn->nomor_kamar,
                        'status_reservasi' => 'check_in'
                    ]);


                    // $pesan = Pesanan::find($resev->pesn->id);
                    // $pesan->update(
                    //     [
                    //         'nomor_kamar' => json_encode([])
                    //     ]
                    //     );

                    $this->nomorKamar = [];



                    is_null($res)
                    ? $this->dispatch('notify', title: 'fail', message: 'Check In dan Pembayaran gagal dilakukan')
                    : $this->dispatch('notify', title: 'success', message: 'Check In dan  Pembayaran berhasil dilakukan');
                    $this->showModalCheckIn = false;
                
                }else{
                    $this->dispatch('notify', title: 'fail', message: 'Pembayaran gagal!');
                    $this->showModalCheckIn = false;
                }

        }





        public function checkOut($id) 
        {
            Carbon::setLocale('id');
    
            $this->id = $id;
    
            $resev = Reservasi::where('id', $id)
                ->with('pesanan.diskon', 'pesanan.harga', 'tamu', 'pesanan.kamar.jenisKamar', 'pembayaran.user')
                ->first();

         
            if ($resev) {
    
                $this->no_reservasi = $resev->no_reservasi;
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
                
                // dd($this->total_harga);
    
                // Mapping data pesanan ke array
                $pesanan = $resev->pesanan->map(function ($item) {
                    return [
                'id_pesanan' => $item['id'],

                        'no_kamar' => $item['kamar']['no_kamar'], 
                        'id_jenis_kamar' => $item['kamar']['id_jenis_kamar'],
                        'jenisKamar' => $item['kamar']['jenisKamar']['tipe_kamar'] . ' - ' . $item['kamar']['jenisKamar']['jenis_ranjang'],
                        'harga_kamar' => $item['harga_kamar'],
                        'harga_akhir' => $item['harga_akhir'],
                        'jumlah_malam' => $item['jumlah_malam'],
                        'nomor_kamar' => $item['nomor_kamar'],
                        'persentase_diskon' =>  isset($item['diskon']) ? $item['diskon']['persentase'] : 0,
                        'persentase_kenaikan_harga' => isset($item['harga']) ? $item['harga']['persentase_kenaikan_harga'] : 0,
                        'subtotal' => $item['subtotal']
                    ];
                });


                // dd($pesanan);


            $this->pesanan = $pesanan;
            
            $this->nomorKamar = $pesanan->mapWithKeys(function ($item) {
                // Decode JSON untuk mendapatkan array id_kamar
                $kamarIds = json_decode($item['nomor_kamar'], true);
            
                // Pastikan $kamarIds adalah array dan tidak kosong
                if (is_array($kamarIds) && !empty($kamarIds)) {
                    // Ambil semua data kamar yang ID-nya ada dalam $kamarIds
                    $kamar = Kamar::whereIn('id', $kamarIds)->get(['id', 'no_kamar', 'status_no_kamar']);
                } else {
                    // Jika tidak ada ID kamar, inisialisasi $kamar sebagai koleksi kosong
                    $kamar = collect();
                }
            
                // Ambil hanya no_kamar dan status_no_kamar dari setiap kamar
                $result = $kamar->map(function ($k) use ($item) {
                    return [
                'id_pesanan' => $item['id_pesanan'], // Menyimpan id_pesanan

                        'no_kamar' => $k->no_kamar,
                        'status_no_kamar' => $k->status_no_kamar,
                        'status_pemesanan' => $k->status_pemesanan

                    ];
                });
            
                // Menggunakan id_pesanan sebagai kunci
                return $result; // Pastikan id_pesanan ada dalam $item
            });
            
            // dd($hargaAkhirList,$hargaDasarList);

            // dd($this->nomorKamar);

                
    
    
                $this->hargaDasarList = $pesanan->mapWithKeys(function ($item) {
                    return [$item['no_kamar'] => $item['harga_akhir'] ]; // Akses no_kamar dan harga_akhir sebagai array key
                });
                
                $this->hargaAkhirList = $pesanan->mapWithKeys(function ($item) {
                    return [$item['no_kamar'] => floatval( $item['harga_akhir']) * $this->jumlahHari ]; // Akses no_kamar dan harga_akhir sebagai array key
                });
                
    
    
    
                // $this->pembayaran = $resev->pembayaran ? true : false;
                $this->jumlahPembayaran = $resev->pembayaran ? $resev->pembayaran->jumlah_pembayaran : null;
                $this->kembalian = $resev->pembayaran ? $resev->pembayaran->kembalian : null;
                $this->user = $resev->pembayaran ? $resev->pembayaran->user->username : null;
    
                
            } else {
                $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
            }
    
            $this->showModalCheckOut = true;
        }






        public function submitCheckOut(){
            
            $denda = (float) $this->converDescimal($this->Idenda);
            $res = Reservasi::find($this->id);

            $res->update([
                'status_reservasi' => 'check_out',
                'nomor_kamar_pemesanan' => json_encode([]),
                'denda' => $denda,
                'keterangan' => $this->Iketerangan
            ]);

            is_null($res)
            ? $this->dispatch('notify', title: 'fail', message: 'Check Out gagal dilakukan')
            : $this->dispatch('notify', title: 'success', message: 'Check out berhasil dilakukan');
            $this->showModalCheckOut = false;

        }










































    
    public function batal($id) {
        $this->id = $id;

        $resev = Reservasi::where('id',$id)->first();
        // dd($resev);
        if ($resev) {
            $this->no_reservasi = $resev->no_reservasi;
        }else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalBatal = true;
    }


    public function submitBatal(){
        $batal = Reservasi::find($this->id)->update(
            [
                'status_reservasi' => 'batal'
            ]
        );

        is_null($batal)
        ? $this->dispatch('notify', title: 'fail', message: 'Pembatalan reservasi gagal dilakukan')
        : $this->dispatch('notify', title: 'success', message: 'Pembatalan reservasi berhasil dilakukan');
        $this->showModalBatal = false;
    }

    public function selesai($id) {
        $this->id = $id;

        $resev = Reservasi::where('id',$id)->first();
        // dd($resev);
        if ($resev) {
            $this->no_reservasi = $resev->no_reservasi;
        }else {
            $this->dispatch('notify', title: 'fail', message: 'Reservasi tidak ditemukan');
        }

        $this->showModalSelesai = true;
    }


    public function submitSelesai(){
        // Menggunakan Query Builder untuk memperbarui status_reservasi tanpa mengubah updated_at
        $selesai = DB::table('reservasi')->where('id', $this->id)->update([
            'status_reservasi' => 'selesai',
            // Tidak menambahkan kolom updated_at di sini
        ]);
    
        is_null($selesai)
        ? $this->dispatch('notify', title: 'fail', message: 'Reservasi gagal diselesaikan')
        : $this->dispatch('notify', title: 'success', message: 'Reservasi berhasil diselesaikan');
        
        $this->showModalSelesai = false;
    }
    
    public function render()
    {
        return view('livewire..reservasi.reservasi-index',
        [
          'data' => Reservasi::with('pesanan.kamar','tamu','pesanan.diskon','pesanan.harga','pembayaran')
            ->whereNotIn('status_reservasi', ['selesai', 'batal']) // Menambahkan filter status_reservasi
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->paginate),

        ]
    );
    }
}



