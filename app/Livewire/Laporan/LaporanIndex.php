<?php 

namespace App\Livewire\Laporan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Reservasi;
use App\Models\Pesanan;
use App\Models\Kamar;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class LaporanIndex extends Component
{
    use WithPagination;

    public $tanggalMulai;
    public $tanggalSelesai;
    public $totalPendapatan = 0;
    public $pendapatanKamar = 0;
    public $totalDiskon = 0;
    public $pendapatanPerJenisKamar = [];
    public $revPar = 0;
    public $adr = 0;
    public $occupancyRate = 0;

    protected $rules = [
        'tanggalMulai' => 'required|date',
        'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
    ];

    public function mount()
    {
        $this->tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tanggalSelesai = Carbon::now()->endOfMonth()->toDateString();
        $this->hitungTotalPendapatan();
    }

    public function updated()
    {
        $this->hitungTotalPendapatan();
    }

    public function hitungTotalPendapatan()
    {
        $this->validate();
        
        // Hitung total pendapatan kamar
        $this->pendapatanKamar = Pesanan::whereHas('reservasi', function($query) {
            $query->whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
                  ->where('status_reservasi', 'selesai');
        })->sum('subtotal');

        // Hitung total diskon
        $this->totalDiskon = Pesanan::whereHas('reservasi', function($query) {
            $query->whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
                  ->where('status_reservasi', 'selesai');
        })->sum(DB::raw('(harga_kamar * jumlah_malam) - harga_akhir'));

        // Hitung pendapatan per jenis kamar
        $this->pendapatanPerJenisKamar = Pesanan::select(
                'jenis_kamar.tipe_kamar',
                DB::raw('SUM(pesanan.subtotal) as total')
            )
            ->join('kamar', 'pesanan.id_kamar', '=', 'kamar.id')
            ->join('jenis_kamar', 'kamar.id_jenis_kamar', '=', 'jenis_kamar.id')
            ->whereHas('reservasi', function($query) {
                $query->whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
                      ->where('status_reservasi', 'selesai');
            })
            ->groupBy('jenis_kamar.tipe_kamar')
            ->get();

        // Hitung metrik kinerja
        $start = Carbon::parse($this->tanggalMulai);
        $end = Carbon::parse($this->tanggalSelesai);
        $days = $start->diffInDays($end) + 1;
        $totalKamar = Kamar::count();
        $totalKamarTerjual = Reservasi::whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
            ->where('status_reservasi', 'selesai')
            ->sum('jumlah_kamar');

        // RevPAR
        $this->revPar = $totalKamar > 0 ? $this->pendapatanKamar / ($totalKamar * $days) : 0;
        
        // ADR
        $this->adr = $totalKamarTerjual > 0 ? $this->pendapatanKamar / $totalKamarTerjual : 0;
        
        // Occupancy Rate
        $this->occupancyRate = ($totalKamar * $days) > 0 
            ? ($totalKamarTerjual / ($totalKamar * $days)) * 100 
            : 0;

        $this->totalPendapatan = $this->pendapatanKamar;
    }

    public function render()
    {
        $data = Reservasi::whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
            ->where('status_reservasi', 'selesai')
            ->orderBy('tanggal_check_out', 'desc')
            ->paginate(10);

        return view('livewire.laporan.laporan-index', compact('data'));
    }
}