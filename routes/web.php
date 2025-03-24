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
        $today = now()->format('Y-m-d');
        
        $data = [
            // Statistik Utama
            'totalKamar' => Kamar::count(),
            'kamarTersedia' => Kamar::where('status_kamar', 'tersedia')->count(),
            'reservasiHariIni' => Reservasi::whereDate('tanggal_check_in', $today)->count(),
            'tamuCheckIn' => Reservasi::whereDate('tanggal_check_in', $today)
                              ->where('status_reservasi', 'check_in')->count(),
            
            // Data Terkini
            'reservasiTerbaru' => Reservasi::with('tamu')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get(),
            'keluhanAktif' => Keluhan::where('status_keluhan', 'diproses')
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get(),
            
            // Statistik Tambahan
            'totalDiskonAktif' => Diskon::where('tanggal_mulai', '<=', $today)
                                  ->where('tanggal_berakhir', '>=', $today)
                                  ->count(),
            'kamarPerbaikan' => Kamar::where('status_kamar', 'perbaikan')->count(),
            'occupancyRate' => round((Reservasi::whereDate('tanggal_check_in', $today)
                                    ->count() / Kamar::count()) * 100, 2),
            'totalPendapatanHariIni' => Pembayaran::whereDate('created_at', $today)
                                        ->sum('jumlah_pembayaran')
        ];
    
        return view('pemilik.index', $data);
    })->middleware('role:pemilik')->name('pemilik');


    Route::get('/resepsionis', function () {
        // $today = now()->format('Y-m-d');
        
        // $data = [
        //     // Statistik Utama
        //     'totalKamar' => Kamar::count(),
        //     'kamarTersedia' => Kamar::where('status_kamar', 'tersedia')->count(),
        //     'reservasiHariIni' => Reservasi::whereDate('tanggal_check_in', $today)->count(),
        //     'tamuCheckIn' => Reservasi::whereDate('tanggal_check_in', $today)
        //                       ->where('status_reservasi', 'check_in')->count(),
            
        //     // Data Terkini
        //     'reservasiTerbaru' => Reservasi::with('tamu')
        //                             ->orderBy('created_at', 'desc')
        //                             ->take(5)
        //                             ->get(),
        //     'keluhanAktif' => Keluhan::where('status_keluhan', 'diproses')
        //                        ->orderBy('created_at', 'desc')
        //                        ->take(5)
        //                        ->get(),
            
        //     // Statistik Tambahan
        //     'totalDiskonAktif' => Diskon::where('tanggal_mulai', '<=', $today)
        //                           ->where('tanggal_berakhir', '>=', $today)
        //                           ->count(),
        //     'kamarPerbaikan' => Kamar::where('status_kamar', 'perbaikan')->count(),
        //     // 'occupancyRate' => round((Reservasi::whereDate('tanggal_check_in', $today)
        //     //                         ->count() / Kamar::count()) * 100, 2),
        //     'totalPendapatanHariIni' => Pembayaran::whereDate('created_at', $today)
        //                                 ->sum('jumlah_pembayaran')
        // ];
    
        return view('resepsionis.index');
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
        
   
        // Di Reservasi old
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


        // Di Reservasi new in use
        Route::get('/ketersediaan-kamar/{tanggal_check_in}/{tanggal_check_out}/{id_jenis_kamar}/{jumlah_kamar}', function ($tanggal_check_in, $tanggal_check_out, $id_jenis_kamar, $jumlah_kamar) {
            // Validasi input
            if (!strtotime($tanggal_check_in) || !strtotime($tanggal_check_out) || $tanggal_check_in >= $tanggal_check_out) {
                return response()->json(['error' => 'Tanggal tidak valid'], 400);
            }

            if (!is_numeric($id_jenis_kamar) || !is_numeric($jumlah_kamar)) {
                return response()->json(['error' => 'ID jenis kamar atau jumlah kamar tidak valid'], 400);
            }

            $checkIN = Carbon::parse($tanggal_check_in)->format('Y-m-d');
            $checkOUT = Carbon::parse($tanggal_check_out)->format('Y-m-d');
            
            Carbon::setLocale('id');
            $tglCheckIN = Carbon::parse($tanggal_check_in)->translatedFormat('d F Y');
            $tglCheckOUT = Carbon::parse($tanggal_check_out)->translatedFormat('d F Y');

            // Ambil total kamar tersedia untuk jenis kamar tertentu
            $total_kamar = DB::table('kamar')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->where('status_kamar', 'tersedia')
                ->count();

            // Ambil data kamar
            $kamar = DB::table('kamar')->where('id_jenis_kamar', $id_jenis_kamar)->first();
            if (!$kamar) {
                return response()->json(['error' => 'ID jenis kamar tidak ditemukan'], 400);
            }

            // $k
            // Mengambil kamar yang tersedia berdasarkan id_jenis_kamar
            $no_kamar = DB::table('kamar')
            ->where('id_jenis_kamar', $id_jenis_kamar)  // Filter berdasarkan id_jenis_kamar
            ->where('status_kamar', 'tersedia')  // Status kamar harus tersedia
            ->select('no_kamar')  // Ambil hanya kolom no_kamar
            ->limit($jumlah_kamar)  
            ->pluck('no_kamar'); 


            // Ambil diskon jika ada
            $diskon = DB::table('diskon')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->whereDate('tanggal_mulai', '<=', $checkIN)
                ->whereDate('tanggal_berakhir', '>=', $checkIN)
                ->first();

            // Ambil harga khusus jika ada
            $harga = DB::table('harga')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->whereDate('tanggal_mulai', '<=', $checkIN)
                ->whereDate('tanggal_berakhir', '>=', $checkIN)
                ->first();

            // Harga Dasar
            $hargaDasar = $kamar->harga_kamar;

            // Harga Khusus jika ada kenaikan harga
            $hargaKhusus = $harga ? $hargaDasar * (1 + ($harga->persentase_kenaikan_harga / 100)) : null;

            // Hitung harga akhir dengan diskon
            $totalHarga = $diskon 
                ? ($hargaKhusus ? $hargaKhusus * (1 - ($diskon->persentase / 100)) : $hargaDasar * (1 - ($diskon->persentase / 100)))
                : ($hargaKhusus ?? $hargaDasar);

            // **Cek Overbooking & Buat Ketersediaan Per Hari**
            $ketersediaan_per_hari = [];
            $total_akumulasi_kamar = 0;
            $tanggal_mulai = Carbon::parse($tanggal_check_in);
            $tanggal_selesai = Carbon::parse($tanggal_check_out);
            
            for ($tanggal = clone $tanggal_mulai; $tanggal < $tanggal_selesai; $tanggal->modify('+1 day')) {
                $tglID = $tanggal->translatedFormat('d F Y');
                $tgl = $tanggal->format('Y-m-d');

                // Hitung jumlah kamar yang sudah dipesan di tanggal ini
                $reservasi_aktif = DB::table('reservasi')
                ->join('pesanan', 'reservasi.id', '=', 'pesanan.id_reservasi')  // Menyambungkan tabel reservasi dan pesanan
                ->join('kamar', 'pesanan.id_kamar', '=', 'kamar.id')  // Menyambungkan pesanan dan kamar
                ->join('jenis_kamar', 'kamar.id_jenis_kamar', '=', 'jenis_kamar.id')  // Menyambungkan kamar dan jenis_kamar
                ->where('jenis_kamar.id', $id_jenis_kamar)  // Filter berdasarkan id_jenis_kamar
                ->whereIn('reservasi.status_reservasi', ['dipesan', 'check_in'])  // Status reservasi aktif
                ->whereDate('reservasi.tanggal_check_in', '<=', $tgl)  // Cek tanggal check-in
                ->whereDate('reservasi.tanggal_check_out', '>', $tgl)  // Cek tanggal check-out
                ->sum('reservasi.jumlah_kamar');  // Menghitung jumlah kamar
            
                    
                // $reservasi_aktif = DB::table('reservasi')
                //     ->whereIn('status_reservasi', ['dipesan', 'check_in'])
                //     ->whereDate('tanggal_check_in', '<=', $tgl)
                //     ->whereDate('tanggal_check_out', '>', $tgl)
                //     ->sum('jumlah_kamar');

                $kamar_tersedia = $total_kamar - $reservasi_aktif;
                $total_akumulasi_kamar += max($kamar_tersedia, 0); // Akumulasi kamar tersedia

                // Tambahkan ke array ketersediaan
                $ketersediaan_per_hari[] = [
                    'tanggal' => $tgl,
                    'kamar_terpakai' => $reservasi_aktif,
                    'kamar_tersedia' => max($kamar_tersedia, 0)
                ];

                // **Cek Jika Overbooking**
                if ($jumlah_kamar > $kamar_tersedia) {
  

                    return response()->json([
                        'error' => $kamar_tersedia == 0 ? "Kamar Tidak tersedia pada tanggal $tglID!" : "Kamar tidak dapat dipesan, pada tanggal $tglID!, Kamar Hanya tersedia $kamar_tersedia kamar.",
                        'tanggal' => $tglID,
                        'kamar_tersedia' => $kamar_tersedia,
                        'jumlah_diminta' => $jumlah_kamar,
                        'total_akumulasi_kamar' => $total_akumulasi_kamar,
                        'ketersediaan_per_hari' => $ketersediaan_per_hari
                    ], 400);
                }
            }

            $elemen_terakhir = end($ketersediaan_per_hari);
            $kamar_tersedia = $elemen_terakhir['kamar_tersedia'];
            // $kamar_tersedia = $elemen_terakhir['kamar_tersedia'] - (float) $jumlah_kamar;


            // dd($no_kamar);
            // Jika tidak overbooking, kembalikan response dengan semua data yang diperlukan
            return response()->json([
                'status' => 'Tersedia',
                'no_kamar' => $no_kamar,
                'message' => "$kamar_tersedia Kamar tersedia, untuk periode $tglCheckIN hingga $tglCheckOUT",
                'total_kamar' => $total_kamar,
                'kamar_tersedia' => $kamar_tersedia,
                'jumlah_kamar_diminta' => $jumlah_kamar,
                'harga_kamar' => number_format($hargaDasar, 2, '.', ''),
                'harga_khusus' => $hargaKhusus ? number_format($hargaKhusus, 2, '.', '') : null,
                'harga_final' => number_format($totalHarga, 2, '.', ''),
                'id_diskon' => $diskon ? $diskon->id : null,
                'persentase_diskon' => $diskon ? $diskon->persentase : 0,
                'id_harga' => $harga ? $harga->id : null,
                'persentase_kenaikan_harga' => $harga ? $harga->persentase_kenaikan_harga : 0,
                'total_akumulasi_kamar' => $total_akumulasi_kamar,
                'ketersediaan_per_hari' => $ketersediaan_per_hari
            ]);
        });





        Route::get('/ketersediaan-kamarR', function () {
            return Reservasi::whereIn('reservasi.status_reservasi', ['dipesan', 'check_in'])->get();
        });





        // Di table Kamar old
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

        // Di table Kamar new
        Route::get('/ketersediaan-new/{tanggal_check_in}/{tanggal_check_out}/{id_jenis_kamar}', function ($tanggal_check_in, $tanggal_check_out, $id_jenis_kamar) {


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

            $checkIN = Carbon::parse($tanggal_check_in)->format('Y-m-d');

        
            // Buat array untuk menyimpan ketersediaan per hari
            $ketersediaan_per_hari = [];
            $total_akumulasi_kamar = 0; // Menyimpan total akumulasi kamar

            // Ambil diskon jika ada
            $diskon = DB::table('diskon')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->whereDate('tanggal_mulai', '<=', $checkIN)
                ->whereDate('tanggal_berakhir', '>=', $checkIN)
                ->first();

            // Ambil harga khusus jika ada
            $harga = DB::table('harga')
                ->where('id_jenis_kamar', $id_jenis_kamar)
                ->whereDate('tanggal_mulai', '<=', $checkIN)
                ->whereDate('tanggal_berakhir', '>=', $checkIN)
                ->first();

            // Harga Dasar
            $hargaDasar = $kamar->harga_kamar;

            // Harga Khusus jika ada kenaikan harga
            $hargaKhusus = $harga ? $hargaDasar * (1 + ($harga->persentase_kenaikan_harga / 100)) : null;

            // Hitung harga akhir dengan diskon
            $totalHarga = $diskon 
                ? ($hargaKhusus ? $hargaKhusus * (1 - ($diskon->persentase / 100)) : $hargaDasar * (1 - ($diskon->persentase / 100)))
                : ($hargaKhusus ?? $hargaDasar);


            $tanggal_mulai = Carbon::parse($tanggal_check_in);
            $tanggal_selesai = Carbon::parse($tanggal_check_out);

            for ($tanggal = clone $tanggal_mulai; $tanggal <= $tanggal_selesai; $tanggal->addDay()) { 
                Carbon::setLocale('id');
                $tglID = $tanggal->translatedFormat('d F Y');
                $tgl = $tanggal->format('Y-m-d');




                // Hitung jumlah kamar yang sudah dipesan di tanggal ini
                $reservasi_aktif = DB::table('reservasi')
                ->join('pesanan', 'reservasi.id', '=', 'pesanan.id_reservasi')  // Menyambungkan tabel reservasi dan pesanan
                ->join('kamar', 'pesanan.id_kamar', '=', 'kamar.id')  // Menyambungkan pesanan dan kamar
                ->join('jenis_kamar', 'kamar.id_jenis_kamar', '=', 'jenis_kamar.id')  // Menyambungkan kamar dan jenis_kamar
                ->where('jenis_kamar.id', $id_jenis_kamar)  // Filter berdasarkan id_jenis_kamar
                ->whereIn('reservasi.status_reservasi', ['dipesan', 'check_in'])  // Status reservasi aktif
                ->whereDate('reservasi.tanggal_check_in', '<=', $tgl)  // Cek tanggal check-in
                ->whereDate('reservasi.tanggal_check_out', '>', $tgl)  // Cek tanggal check-out
                ->sum('reservasi.jumlah_kamar');  // Menghitung jumlah kamar
            

                $kamar_tersedia = $total_kamar - $reservasi_aktif;
                $total_akumulasi_kamar += max($kamar_tersedia, 0); // Akumulasi kamar tersedia

       
                // sdasd
                // Simpan hasil per hari
                $ketersediaan_per_hari[] = [
                    'tanggal_ID' => $tglID,
                    'jenis_kamar' => $kamar->jenisKamar->tipe_kamar . ' - '. $kamar->jenisKamar->jenis_ranjang,
                    'total_kamar' => $total_kamar,
                    'kamar_tersedia' => $kamar_tersedia,
                    'harga_kamar' => number_format($totalHarga, 2, '.', ''),
                    'id_diskon' => $diskon ? $diskon->id : null,
                    'persentase_diskon' => $diskon ? $diskon->persentase : 0,
                    'id_harga' => $harga ? $harga->id : null,
                    'persentase_kenaikan_harga' => $harga ? $harga->persentase_kenaikan_harga : 0
                ];

                // Tambahkan ke total akumulasi kamar
                // $total_akumulasi_kamar += $kamar_tersedia;
            }



            return response()->json([
                'ketersediaan_per_hari' => $ketersediaan_per_hari,
                'total_akumulasi_kamar' => $total_akumulasi_kamar
            ]);

        });




        Route::get('/invoice/{idRes}', function($idRes) {
            // dd($idRes); 
            
            Carbon::setLocale('id');
        
            $resev = Reservasi::where('id', $idRes)
                ->with('pesanan.diskon', 'pesanan.harga', 'tamu', 'pesanan.kamar.jenisKamar', 'pembayaran.user')
                ->first();
        
            // dd($resev);
        
            // Mengubah semua properti menjadi array
            $invoiceData = [
                'idTamu' => $resev->tamu->id,
                'namaTamu' => $resev->tamu->nama,
                'alamatTamu' => $resev->tamu->alamat,
                'kotaTamu' => $resev->tamu->kota,
                'emailTamu' => $resev->tamu->email,
                'teleponTamu' => $resev->tamu->no_tlpn,
                'invoiceNumber' => 'INV' . str_pad($resev->tamu->id, 3, '0', STR_PAD_LEFT) . date('Ymd', strtotime($resev->tanggal_check_in)),
                'invoiceDate' => now()->format('Y-m-d'),
                'jumlahKamar' => $resev->jumlah_kamar,
                'total_harga' => $resev->total_harga,
                'denda' => $resev->denda,
                'status_reservasi' => $resev->status_reservasi == 'check_in' ? 'Check in' : ($resev->status_reservasi == 'check_out' ? 'Check out' : $resev->status_reservasi),
                'jumlahHari' => \Carbon\Carbon::parse($resev->tanggal_check_in)->diffInDays($resev->tanggal_check_out),
                'keterangan' => $resev->keterangan,
            ];
        
            // Menambahkan detail pembayaran jika statusnya check_in atau check_out
            if ($resev->status_reservasi == 'check_in' || $resev->status_reservasi == 'check_out') {
                $invoiceData['jumlahPembayaran'] = $resev->pembayaran->jumlah_pembayaran;
                $invoiceData['kembalian'] = $resev->pembayaran->kembalian;
                $invoiceData['user'] = $resev->pembayaran->user->username;
            }
        
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
        
            // Menambahkan pesanan ke dalam array invoiceData
            $invoiceData['invoice'] = $pesanan;
        
            return view('resepsionis.invoice', compact('invoiceData'));
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



        Route::get('/jenis-kamar/diskon', function () {
            
                if (Auth::check()) {
                    // return JenisKamar::whereDoesntHave('diskon')->get();
            return JenisKamar::find(1)->with('diskon')->first();

                } else {
                    return abort(404);
                }
        });

        Route::get('/diskon', function () {
            
            return JenisKamar::find(1)->with('diskon')->first();
        });
                
        Route::get('/jenis-kamar/harga', function () {
                if (Auth::check()) {
                    return JenisKamar::whereDoesntHave('harga')->get();
                } else {
                    return abort(404);
                }
        });



        Route::get('/jenis-kamar/diskon/{tanggal_mulai}/{tanggal_berakhir}', function ($tanggal_mulai, $tanggal_berakhir) {
            if (!Auth::check()) {
                return abort(404);
            }

            return JenisKamar::whereDoesntHave('diskon', function ($query) use ($tanggal_mulai, $tanggal_berakhir) {
                // Cek diskon yang bertabrakan dengan rentang tanggal baru
                $query->where('tanggal_berakhir', '>=', $tanggal_mulai)
                    ->where('tanggal_mulai', '<=', $tanggal_berakhir);
            })->get();
        });

        
        Route::get('/jenis-kamar/harga/{tanggal_mulai}/{tanggal_berakhir}', function ($tanggal_mulai, $tanggal_berakhir) {
            if (!Auth::check()) {
                return abort(404);
            }

            return JenisKamar::whereDoesntHave('harga', function ($query) use ($tanggal_mulai, $tanggal_berakhir) {
                // Cek diskon yang bertabrakan dengan rentang tanggal baru
                $query->where('tanggal_berakhir', '>=', $tanggal_mulai)
                    ->where('tanggal_mulai', '<=', $tanggal_berakhir);
            })->get();
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
