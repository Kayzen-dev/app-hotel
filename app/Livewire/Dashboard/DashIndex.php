<?php 

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Kamar;
use App\Models\Diskon;
use App\Models\Keluhan;
use Livewire\Component;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class DashIndex extends Component
{
    public function render()
    {
        Carbon::setLocale('id');
        $today = now()->format('Y-m-d');

        // Menghitung total kamar dan reservasi untuk perhitungan occupancy rate
        $totalKamar = Kamar::count();
        $totalReservasiHariIni = Reservasi::whereDate('tanggal_check_in', $today)->count();

        $data = [
            // Statistik Utama
            'totalKamar' => $totalKamar,
            'kamarTersedia' => Kamar::where('status_kamar', 'tersedia')->count(),
            'reservasiHariIni' => $totalReservasiHariIni,
            'tamuCheckIn' => Reservasi::whereDate('tanggal_check_in', $today)
                                ->count(),
            
            // Data Terkini
            'reservasiTerbaru' => Reservasi::with('tamu', 'pesanan.kamar.jenisKamar')
                                    ->whereDate('tanggal_check_in', $today)
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get() ?? collect(), // Gunakan `collect()` agar tidak error jika kosong
            
            'keluhanAktif' => Keluhan::where('status_keluhan', 'diproses')
                               ->whereDate('created_at', $today)
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get() ?? collect(),
            
            // Statistik Tambahan
            'totalDiskonAktif' => Diskon::where('tanggal_mulai', '<=', $today)
                                  ->where('tanggal_berakhir', '>=', $today)
                                  ->count(),
            'kamarPerbaikan' => Kamar::where('status_kamar', 'perbaikan')->count(),
            'occupancyRate' => $totalKamar > 0 ? round(($totalReservasiHariIni / $totalKamar) * 100, 2) : 0, // Hindari division by zero
            // 'totalPendapatanHariIni' => Pembayaran::whereDate('created_at', $today)
            //     ->sum(DB::raw('jumlah_pembayaran - COALESCE(kembalian, 0)')) ?? 0
            'totalPendapatanHariIni' => Reservasi::whereDate('tanggal_check_in', $today)
            ->whereHas('pembayaran') // Pastikan ada relasi 'pembayaran' di model Reservasi
            ->join('pembayaran', 'reservasi.id', '=', 'pembayaran.id_reservasi') // Sesuaikan dengan nama kolom yang tepat
            ->sum(DB::raw('pembayaran.jumlah_pembayaran - COALESCE(pembayaran.kembalian, 0)')) ?? 0


        ];
        
        return view('livewire.dashboard.dash-index', $data);
    }
}
