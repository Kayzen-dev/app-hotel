<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 rounded-xl shadow-lg">
            <!-- Header Section -->
            <div class="mb-8 border-b border-gray-200 pb-6">
                <h2 class="text-3xl font-bold text-gray-300 mb-2">📊 Laporan Pendapatan Hotel</h2>
                <p class="text-gray-300">Analisis kinerja keuangan dan operasional hotel</p>
            </div>
    
            <!-- Filter Section -->
<div class="bg-gray-50 rounded-lg p-6 mb-8">
    <div class="flex flex-col md:flex-row items-center gap-4">
        <!-- Input Section (70%) -->
        <div class="w-full md:w-[70%]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input 
                        type="date" 
                        wire:model.live="tanggalMulai" 
                        class="w-full px-4 py-2 input input-bordered transition-all"
                    >
                    @error('tanggalMulai') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input 
                        type="date" 
                        wire:model.live="tanggalSelesai" 
                        class="w-full px-4 py-2 input input-bordered transition-all"
                    >
                    @error('tanggalSelesai') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Button Section (30%) -->
        <div class="w-full md:w-[30%] self-end">
            <div class="flex flex-col items-stretch gap-2 h-full">
                <label class="block text-sm font-medium text-gray-700 invisible md:visible">Cetak Laporan</label>
                <a 
                    href="{{ route('laporan.pdf', [
                        'start' => $tanggalMulai,
                        'end' => $tanggalSelesai
                    ]) }}" 
                    target="_blank"
                    class="btn btn-neutral w-full"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </div>
</div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-red-50 p-6 rounded-xl">
                    <dt class="text-sm font-medium text-red-600">Total Reservasi</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">{{ $totalReservasi }}</dd>
                </div>
                <div class="bg-green-50 p-6 rounded-xl">
                    <dt class="text-sm font-medium text-green-600">Total Pendapatan</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</dd>
                </div>
            </div>
    
    
    
            <!-- Revenue Tables -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pendapatan per Jenis Kamar</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Jenis kamar</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pendapatanPerJenisKamar as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->tipe_kamar }}</td>
                                <td class="px-6 py-4 text-right text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $data->links() }}
                </div>
            </div>
            
    
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Transaksi</h3>
                </div>
            
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">No. Reservasi</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Nama Tamu</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Jumlah Kamar</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Kamar</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Tgl Check-in</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Tgl Check-out</th>
                                <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Total Harga</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($data as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->no_reservasi }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $item->tamu->nama }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $item->jumlah_kamar }} Kamar</td>
                                    <td class="px-6 py-4 text-gray-900">
                                        @foreach($item->pesanan as $pesanan)
                                            {{ $pesanan->kamar->jenisKamar->tipe_kamar }} - {{ $pesanan->kamar->jenisKamar->jenis_ranjang }}<br>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">{{ Carbon\Carbon::parse($item->tanggal_check_in)->isoFormat('D MMM Y') }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ Carbon\Carbon::parse($item->tanggal_check_out)->isoFormat('D MMM Y') }}</td>
                                    <td class="px-6 py-4 text-gray-900 text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                        <div class="divider">

                        </div>
                        <!-- Tambahkan footer untuk total -->
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-right font-bold text-gray-900">Total Keseluruhan</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-200">
                    {{ $data->links() }}
                </div>
            </div>
    
</div>