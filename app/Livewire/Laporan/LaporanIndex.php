<?php 

namespace App\Livewire\Laporan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Reservasi;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class LaporanIndex extends Component
{
    use WithPagination;

    public $tanggalMulai;
    public $tanggalSelesai;
    public $totalPendapatan = 0;
    public $totalReservasi = 0;
    public $totalDenda = 0;
    public $pendapatanPerJenisKamar = [];

    protected $rules = [
        'tanggalMulai' => 'required|date',
        'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
    ];

    public function mount()
    {
        $this->tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tanggalSelesai = Carbon::now()->endOfMonth()->toDateString();
        $this->updateData();
    }

    public function updated()
    {
        $this->validate();
        $this->updateData();
    }

    private function updateData()
    {
        // Hitung semua total reservasi 
        // $this->totalReservasi = Reservasi::where('status_reservasi', '!=', 'batal')
        //     ->where(function ($query) {
        //         $this->applyDateFilter($query);
        //     })
        //     ->count();

// hitung reservasi selesai
            $this->totalReservasi = Reservasi::where('status_reservasi','selesai')
            ->where(function ($query) {
                $this->applyDateFilter($query);
            })
            ->count();

        // Hitung total pendapatan
        $this->totalPendapatan = Reservasi::where('status_reservasi', 'selesai')
            ->where(function ($query) {
                $this->applyDateFilter($query);
            })
            ->sum('total_harga');

        $this->totalDenda = Reservasi::where('status_reservasi', 'selesai')
            ->where(function ($query) {
                $this->applyDateFilter($query);
            })
            ->sum('denda');

            $this->pendapatanPerJenisKamar = Reservasi::select(
                'jenis_kamar.tipe_kamar',
                DB::raw('SUM(reservasi.jumlah_kamar * pesanan.harga_akhir * pesanan.jumlah_malam) as total')
            )
            ->join('pesanan', 'reservasi.id', '=', 'pesanan.id_reservasi')
            ->join('kamar', 'pesanan.id_kamar', '=', 'kamar.id')
            ->join('jenis_kamar', 'kamar.id_jenis_kamar', '=', 'jenis_kamar.id')
            ->where('status_reservasi', 'selesai')
            ->where(function ($query) {
                $this->applyDateFilter($query);
            })
            ->groupBy('jenis_kamar.tipe_kamar')
            ->get();
    }

    private function applyDateFilter($query)
    {
        $query->where(function ($q) {
            $q->whereBetween('tanggal_check_in', [$this->tanggalMulai, $this->tanggalSelesai])
                ->orWhereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
                ->orWhere(function ($sub) {
                    $sub->where('tanggal_check_in', '<', $this->tanggalMulai)
                        ->where('tanggal_check_out', '>', $this->tanggalSelesai);
                });
        });
    }

    public function render()
    {
        $data = Reservasi::with('tamu','pesanan.kamar.jenisKamar')
            ->where('status_reservasi', 'selesai')
            ->where(function ($query) {
                $this->applyDateFilter($query);
            })
            ->orderBy('tanggal_check_out', 'desc')
            ->get();

        return view('livewire.laporan.laporan-index', [
            'data' => $data,
            'pendapatanPerJenisKamar' => $this->pendapatanPerJenisKamar,
            'totalReservasi' => $this->totalReservasi,
            'totalPendapatan' => $this->totalPendapatan
        ]);
    }
}