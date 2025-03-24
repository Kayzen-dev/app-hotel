<?php

namespace App\Livewire\Dashboard;

use App\Models\Kamar;
use App\Models\Diskon;
use App\Models\Keluhan;
use Livewire\Component;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use Carbon\Carbon;

class DashIndex extends Component
{
    


    public function render()
    {
        Carbon::setLocale('id');

        $today = now()->format('Y-m-d');
        
        $data = [
            // Statistik Utama
            'totalKamar' => Kamar::count(),
            'kamarTersedia' => Kamar::where('status_kamar', 'tersedia')->count(),
            'reservasiHariIni' => Reservasi::whereDate('tanggal_check_in', $today)->whereIn('status_reservasi', ['dipesan', 'check_in'])->count(),
            'tamuCheckIn' => Reservasi::where('status_reservasi', 'check_in')->count(),
            
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
        
        return view('livewire.dashboard.dash-index', $data);
    }
}
