<?php

namespace App\Livewire\Laporan;

use Carbon\Carbon;
use App\Models\Riwayat;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanIndex extends Component
{
    use WithPagination;

    public $tanggalMulai;
    public $tanggalSelesai;
    public $totalPendapatan = 0;

    public function mount()
    {
        $this->tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tanggalSelesai = Carbon::now()->endOfMonth()->toDateString();
    }

    public function updated()
    {
        $this->hitungTotalPendapatan();
    }

    public function hitungTotalPendapatan()
    {
        $this->totalPendapatan = Riwayat::whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
            ->where('status_reservasi', 'selesai')
            ->sum('total_harga');
    }
    
    public function render()
    {
        $data = Riwayat::whereBetween('tanggal_check_out', [$this->tanggalMulai, $this->tanggalSelesai])
            ->where('status_reservasi', 'selesai')
            ->orderBy('tanggal_check_out', 'desc')
            ->paginate(10);
        return view('livewire..laporan.laporan-index', compact('data'));
    }
}
