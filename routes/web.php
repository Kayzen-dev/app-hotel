<?php

use Carbon\Carbon;
use App\Models\Tamu;
use App\Models\Harga;
use App\Models\Kamar;
use App\Models\Diskon;
use App\Models\Keluhan;
use App\Models\Karyawan;
use App\Models\Reservasi;
use App\Models\JenisKamar;
use App\Models\Pembayaran;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return redirect()->route('login');
})->middleware('userAkses')->name('home');

Route::get('/guest', function(){
    return view('karyawan');
})->middleware('guest')->name('karyawan');




Route::middleware(['auth','verified','proses'])->group(function(){

    

    Route::get('/pemilik', function () {
        return view('pemilik.index');
    })->middleware('role:pemilik')->name('pemilik');


    Route::get('/resepsionis', function () {
        $totalKamar = Kamar::count();
        $totalReservasi = Reservasi::count();
        $totalKeluhan = Keluhan::count();
        $kamarDipesan = Kamar::where('status_kamar', 'terisi')->count();

        $tanggalHariIni = Carbon::today();

        $kamarTersedia = Kamar::where('status_kamar', 'tersedia')->count();
        $jumlahTamu = Tamu::count();
        $jumlahKaryawan = Karyawan::count();
        $totalPembayaran = Pembayaran::sum('jumlah_pembayaran');

        return view('resepsionis.index',compact(
            'totalKamar', 'totalReservasi', 'totalKeluhan', 
            'kamarDipesan', 'kamarTersedia', 'jumlahTamu', 
            'jumlahKaryawan', 'totalPembayaran'
        ));
    })->middleware('role:resepsionis')->name('resepsionis');


    

    Route::prefix('pemilik')->middleware('role:pemilik')->group(function () {

        Route::get('/data/karyawan',  \App\Livewire\Karyawan\KaryawanIndex::class)->name('pemilik.karyawan.index');

        Route::get('/laporan', function () {
            return view('pemilik.laporan');
        })->name('pemilik.laporan.index');

        Route::get('/riwayat', function () {
            return view('pemilik.riwayat');
        })->name('pemilik.riwayat.index');

        Route::get('/data/akun', \App\Livewire\Admin\AdminIndex::class)->name('pemilik.akun.index');
        

        Route::get('/export-users', function () {
            return Excel::download(new UsersExport(), 'data_akun.xlsx');
        })->name('export.user');
 
 
                Route::get('/export-users-pdf', function() {
 
                    $users = App\Models\User::query()->with('roles')->get();
        
                // Kirim data ke view
                $pdf = Pdf::loadView('Pdf.users', compact('users'));
        
                // Unduh file PDF
                return $pdf->download('data_akun.pdf');
             })->name('export-users-pdf');

    });





    Route::prefix('resepsionis')->middleware('role:resepsionis')->group(function () {
        
   
        // Di Reservasi
        Route::get('/ketersediaan-kamar/{tanggal_check_in}/{tanggal_check_out}/{id_jenis_kamar}', function ($tanggal_check_in, $tanggal_check_out, $id_jenis_kamar) {
            // Validasi input sederhana
            if (!strtotime($tanggal_check_in) || !strtotime($tanggal_check_out) || $tanggal_check_in >= $tanggal_check_out) {
                return response()->json(['error' => 'Tanggal tidak valid'], 400);
            }
        
            if (!is_numeric($id_jenis_kamar)) {
                return response()->json(['error' => 'ID jenis kamar tidak valid'], 400);
            }

            

            $checkIN = Carbon::parse($tanggal_check_in)->format('Y-m-d');
            // $checkOut = Carbon::parse($tanggal_check_out)->format('Y-m-d');

            // Ambil diskon dengan validasi yang sesuai
            $diskon = Diskon::where('id_jenis_kamar', $id_jenis_kamar)
                ->whereDate('tanggal_mulai', '<=', $checkIN)  // Pastikan check-in tidak lebih awal dari tanggal_mulai
                ->whereDate('tanggal_berakhir', '>=', $checkIN) // Pastikan check-in masih dalam periode diskon
                ->first();

            // Cari harga berdasarkan id_jenis_kamar dan rentang tanggal check-in dan check-out
            $harga = Harga::where('id_jenis_kamar', $id_jenis_kamar)
                ->whereDate('tanggal_mulai', '<=', $checkIN)   // Harga harus berlaku saat check-in
                ->whereDate('tanggal_berakhir', '>=', $checkIN) // Harga harus berlaku saat check-in
                ->first();


        
            // Ambil total kamar yang tersedia untuk jenis kamar tertentu
            $total_kamar = DB::table('kamar')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->where('status_kamar', 'tersedia') // Hanya kamar yang tersedia
                ->count();
            $kamar = Kamar::with('jenisKamar')->where('id_jenis_kamar', $id_jenis_kamar)->first();

            
            if (!$kamar) {
                return response()->json(['error' => 'ID jenis kamar tidak ditemukan'], 400);
            }
        
            // Buat array untuk menyimpan ketersediaan per hari
            $ketersediaan_per_hari = [];
            $total_akumulasi_kamar = 0; // Menyimpan total akumulasi kamar
        
            $tanggal_mulai = Carbon::parse($tanggal_check_in);
            $tanggal_selesai = Carbon::parse($tanggal_check_out);
            for ($tanggal = clone $tanggal_mulai; $tanggal < $tanggal_selesai; $tanggal->modify('+1 day')) {
                Carbon::setLocale('id');
                $tglID = $tanggal->translatedFormat('d F Y');
                $tgl = $tanggal->format('Y-m-d');


                $kamar_tersedia = $total_kamar;

                // Simpan hasil per hari
                $ketersediaan_per_hari[] = [
                    'tanggal' => $tgl,
                    'tanggal_ID' => $tglID,
                    'jenis_kamar' => $kamar->jenisKamar->tipe_kamar . ' - '. $kamar->jenisKamar->jenis_ranjang,
                    'total_kamar' => $total_kamar,
                    'kamar_tersedia' => $kamar_tersedia
                ];
        
                // Tambahkan ke total akumulasi kamar
                $total_akumulasi_kamar += $kamar_tersedia;
            }




            $hargaDasar = $kamar->harga_kamar;

            $hargaKhusus = $harga ? $hargaDasar * (1 + ($harga->persentase_kenaikan_harga / 100)) : null;

            $totalHarga = $diskon ? ( $hargaKhusus ? $hargaKhusus * (1 - ($diskon->persentase / 100)) : ($diskon ? $hargaDasar * (1 - ($diskon->persentase / 100)) : null)) :
            
            ( $harga ? $hargaDasar * (1 + ($harga->persentase_kenaikan_harga / 100)) : $hargaDasar ) ;


            return response()->json([
                'harga' => number_format($totalHarga, 2, '.', ''),
                'ketersediaan_per_hari' => $ketersediaan_per_hari,
                'total_akumulasi_kamar' => $total_akumulasi_kamar,
                'id_diskon' => $diskon ? $diskon->id : null,
                'persentase_diskon' => $diskon ? $diskon->persentase : 0,
                'id_harga' => $harga ? $harga->id : null,
                'persentase_kenaikan_harga' => $harga ? $harga->persentase_kenaikan_harga : 0
            ]);

        });

        // Di table Kamar
        Route::get('/ketersediaan/{tanggal_check_in}/{tanggal_check_out}/{id_jenis_kamar}', function ($tanggal_check_in, $tanggal_check_out, $id_jenis_kamar) {


            // Validasi input sederhana
            if (!strtotime($tanggal_check_in) || !strtotime($tanggal_check_out) || $tanggal_check_in >= $tanggal_check_out) {
                return response()->json(['error' => 'Tanggal tidak valid'], 400);
            }
        
            if (!is_numeric($id_jenis_kamar)) {
                return response()->json(['error' => 'ID jenis kamar tidak valid'], 400);
            }

            

        
            // Ambil total kamar yang tersedia untuk jenis kamar tertentu
            $total_kamar = DB::table('kamar')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->where('status_kamar', 'tersedia') // Hanya kamar yang tersedia
                ->count();

            $kamar = Kamar::with('jenisKamar')->where('id_jenis_kamar', $id_jenis_kamar)->first();

            if (!$kamar) {
                return response()->json(['error' => 'ID jenis kamar tidak ditemukan'], 400);
            }
        
            // Buat array untuk menyimpan ketersediaan per hari
            $ketersediaan_per_hari = [];
            $total_akumulasi_kamar = 0; // Menyimpan total akumulasi kamar



            $tanggal_mulai = Carbon::parse($tanggal_check_in);
            $tanggal_selesai = Carbon::parse($tanggal_check_out);

            for ($tanggal = clone $tanggal_mulai; $tanggal <= $tanggal_selesai; $tanggal->addDay()) { 
                Carbon::setLocale('id');
                $tglID = $tanggal->translatedFormat('d F Y');

                $kamar_tersedia = $total_kamar;

                // sdasd
                // Simpan hasil per hari
                $ketersediaan_per_hari[] = [
                    'tanggal_ID' => $tglID,
                    'jenis_kamar' => $kamar->jenisKamar->tipe_kamar . ' - '. $kamar->jenisKamar->jenis_ranjang,
                    'total_kamar' => $total_kamar,
                    'kamar_tersedia' => $kamar_tersedia
                ];

                // Tambahkan ke total akumulasi kamar
                $total_akumulasi_kamar += $kamar_tersedia;
            }



            return response()->json([
                'ketersediaan_per_hari' => $ketersediaan_per_hari,
                'total_akumulasi_kamar' => $total_akumulasi_kamar
            ]);

        });



    Route::get('/invoice/{idTamu}', function($idTamu) {
        // Ambil data tamu dan reservasi dengan status tertentu
        // $tamu = Tamu::with([
        //     'reservasi' => function ($query) {
        //         $query->with(['kamar.jenisKamar', 'pembayaran'])
        //             ->whereIn('status_reservasi', ['dipesan', 'check_in', 'check_out']);
        //     }
        // ])->findOrFail($idTamu);

        // // Mapping data reservasi ke array
        // $reservasi = $tamu->reservasi->map(function ($item) {
        //     return [
        //         'tipe_kamar' => $item->kamar->jenisKamar->tipe_kamar,
        //         'jenis_ranjang' => $item->kamar->jenisKamar->jenis_ranjang,
        //         'no_kamar' => $item->kamar->no_kamar,
        //         'durasi' => \Carbon\Carbon::parse($item->tanggal_check_in)->diffInDays($item->tanggal_check_out),
        //         'harga_kamar' => $item->kamar->harga_kamar,
        //         'total_harga' => $item->total_harga,
        //         'status' => ucfirst($item->status_reservasi)
        //     ];
        // });


        $reservasi = [
            'tipe_kamar' => "KING",
                    'jenis_ranjang' => "TWIN",
                    'no_kamar' => "KING",
                    'durasi' => 3,
                    'harga_kamar' => "30000.00",
                    'total_harga' => "40000000.00",
                    'status' => "Check in"
        ];

        // Informasi invoice
        $invoiceData = [
            'invoiceNumber' => 'INV' . str_pad(3, 5, '0', STR_PAD_LEFT),
            'invoiceDate' => now()->format('d/m/Y'),
            'namaTamu' => "Andi",
            'alamatTamu' => "Sukaresik,Sidamulih",
            'kotaTamu' => "Pangandaran",
            'emailTamu' => "Andi@gmail.com",
            'teleponTamu' => "99892342",
            'reservasi' => $reservasi,
            'totalHarga' => "5000000.00",
        ];

        return view('resepsionis.invoice', $invoiceData);
    
    })->name('resep.invoice');
        


        Route::get('/data-tamu', function () {
                
            if (Auth::check()) {
                return Tamu::all()->toArray();
            }else{
                return abort('404');
            }
    
        });




        Route::get('/jenis-kamar', function () {
        
            if (Auth::check()) {
                return JenisKamar::all()->toArray();
            }else{
                return abort('404');
            }
    
        });


   

        Route::get('/jenis-kamar/diskon/{tanggal_mulai}/{tanggal_berakhir}', function ($tanggal_mulai, $tanggal_berakhir) {
            if (!Auth::check()) {
                return abort(404);
            }
        
            return JenisKamar::with(['diskon' => function ($query) use ($tanggal_mulai, $tanggal_berakhir) {
                    $query->where(function ($q) use ($tanggal_mulai, $tanggal_berakhir) {
                        $q->where('tanggal_mulai', '>', $tanggal_berakhir)
                          ->orWhere('tanggal_berakhir', '<', $tanggal_mulai);
                    });
                }])
                ->whereHas('diskon', function ($query) use ($tanggal_mulai, $tanggal_berakhir) {
                    $query->where('tanggal_mulai', '>', $tanggal_berakhir)
                          ->orWhere('tanggal_berakhir', '<', $tanggal_mulai);
                })
                ->orWhereDoesntHave('diskon') // Ambil jenis kamar yang belum memiliki diskon
                ->get();
        });


        Route::get('/jenis-kamar/diskon', function () {
            
                if (Auth::check()) {
                    return JenisKamar::whereDoesntHave('diskon')->get();
                } else {
                    return abort(404);
                }
        });
                
        Route::get('/jenis-kamar/harga', function () {
                if (Auth::check()) {
                    return JenisKamar::whereDoesntHave('harga')->get();
                } else {
                    return abort(404);
                }
        });

        

                


        
        Route::get('/kamar', function () {
            return view('resepsionis.kamar');
        })->name('resepsionis.kamar.index');


        Route::get('/tamu', function () {
            return view('resepsionis.tamu');
        })->name('resepsionis.tamu.index');

        Route::get('/reservasi', function () {
            return view('resepsionis.reservasi');
        })->name('resepsionis.reservasi.index');
        // Route::get('/reservasi', \App\Livewire\Reservasi\ReservasiCreate::class)->name('resepsionis.reservasi.index');


        Route::get('/laporan', function () {
            return view('resepsionis.laporan');
        })->name('resepsionis.laporan.index');

        Route::get('/data/reservasi', function () {
            return view('resepsionis.data-reservasi');
        })->name('resepsionis.reservasi.data');

    });





    Route::view('profile', 'profile')->name('profile');

});





require __DIR__.'/auth.php';
