<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-xl shadow-lg p-6 md:p-8">
        <!-- Header Section -->
        <div class="mb-8 border-b border-gray-200 pb-6">
            <h2 class="text-3xl font-bold text-gray-300 mb-2">📊 Laporan Pendapatan Hotel</h2>
            <p class="text-gray-300">Analisis kinerja keuangan dan operasional hotel</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input 
                        type="date" 
                        wire:model="tanggalMulai" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    >
                    @error('tanggalMulai') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input 
                        type="date" 
                        wire:model="tanggalSelesai" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300  text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    >
                    @error('tanggalSelesai') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 p-6 rounded-xl">
                <dt class="text-sm font-medium text-blue-600">Pendapatan Kamar</dt>
                <dd class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($pendapatanKamar, 0, ',', '.') }}</dd>
            </div>
            <div class="bg-red-50 p-6 rounded-xl">
                <dt class="text-sm font-medium text-red-600">Total Diskon</dt>
                <dd class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</dd>
            </div>
            <div class="bg-green-50 p-6 rounded-xl">
                <dt class="text-sm font-medium text-green-600">Total Pendapatan</dt>
                <dd class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</dd>
            </div>
        </div>

        <!-- Revenue Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Pendapatan per Jenis Kamar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pendapatan per Jenis Kamar</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Jenis Kamar</th>
                                <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pendapatanPerJenisKamar as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->tipe_kamar }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Metrik Kinerja -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Metrik Kinerja</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Metrik</th>
                                <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">RevPAR</td>
                                <td class="px-6 py-4 text-right text-gray-900">Rp {{ number_format($revPar, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">ADR</td>
                                <td class="px-6 py-4 text-right text-gray-900">Rp {{ number_format($adr, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">Occupancy Rate</td>
                                <td class="px-6 py-4 text-right text-gray-900">{{ number_format($occupancyRate, 2, ',', '.') }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
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
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Kamar</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Tgl Check-out</th>
                            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->no_reservasi }}</td>
                                <td class="px-6 py-4">{{ $item->tamu->nama }}</td>
                                <td class="px-6 py-4">{{ $item->jumlah_kamar }} Kamar</td>
                                <td class="px-6 py-4">{{ Carbon\Carbon::parse($item->tanggal_check_out)->isoFormat('D MMM Y') }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>