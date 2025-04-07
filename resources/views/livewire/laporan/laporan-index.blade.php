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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-red-50 p-6 rounded-xl">
                    <dt class="text-sm font-medium text-red-600">Total Reservasi</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">{{ $totalReservasi }}</dd>
                </div>
                <div class="bg-green-50 p-6 rounded-xl">
                    <dt class="text-sm font-medium text-green-600">Total Pendapatan Kamar</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</dd>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl">
                    <dt class="text-sm font-medium text-blue-600">Total Denda</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalDenda, 0, ',', '.') }}</dd>
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
                            <tr class="">
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
          
            </div>



<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden" 
        x-data="transaksi()" x-cloak>
        <!-- Header Section -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Detail Transaksi</h3>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Reservasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Tamu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Kamar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kamar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-in</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-out</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Kamar</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Denda</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="item in paginatedData" :key="item.id">
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-4 text-sm text-gray-900 font-medium" x-text="item.no_reservasi"></td>
                            <td class="px-4 py-4 text-sm text-gray-900" x-text="item.tamu.nama"></td>
                            <td class="px-4 py-4 text-sm text-gray-900" x-text="item.jumlah_kamar + ' Kamar'"></td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                <div class="space-y-1">
                                    <template x-if="item.pesanan && item.pesanan.length > 0">
                                        <template x-for="pesanan in item.pesanan" :key="pesanan.id">
                                            <div class="text-gray-700">
                                                <template x-if="pesanan.kamar && pesanan.kamar.jenis_kamar">
                                                    <div>
                                                        <span x-text="pesanan.kamar.jenis_kamar.tipe_kamar"></span>
                                                        <span class="text-gray-500" 
                                                              x-text="'(' + pesanan.kamar.jenis_kamar.jenis_ranjang + ')'"></span>
                                                       
                                                    </div>
                                                </template>
                                                <template x-if="!pesanan.kamar || !pesanan.kamar.jenis_kamar">
                                                    <span class="text-red-500">Data kamar tidak tersedia</span>
                                                </template>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap" 
                                x-text="formatDate(item.tanggal_check_in)"></td>
                            <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap" 
                                x-text="formatDate(item.tanggal_check_out)"></td>
                            <td class="px-4 py-4 text-sm text-gray-900 text-right font-medium" 
                                x-text="'Rp ' + numberFormat(item.total_harga)"></td>
                            <td class="px-4 py-4 text-sm text-red-600 text-right font-medium" 
                                x-text="'Rp ' + numberFormat(item.denda)"></td>
                            <td class="px-4 py-4 text-sm text-gray-900 text-right font-semibold" 
                                x-text="'Rp ' + numberFormat(Number(item.total_harga) + Number(item.denda))"></td>
                        </tr>
                    </template>

                    <tr x-show="paginatedData.length === 0">
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500 text-sm">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                </tbody>

                <tfoot class="border-t border-gray-200 bg-gray-50">
                    <tr>
                        <td></td>
                        <td colspan="7" class="px-4 py-4 text-right text-sm font-bold text-gray-700">
                            Total Keseluruhan:
                        </td>
                        {{-- <td class="px-4 py-4 text-right text-sm font-bold text-gray-900 whitespace-nowrap">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </td> --}}
                        <td class="px-4 py-4 text-right text-sm font-bold text-gray-900 whitespace-nowrap">
                            Rp {{ number_format($totalPendapatan + $totalDenda, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
            <div class="flex justify-between flex-1 sm:hidden">
                <button @click="previousPage" 
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Previous
                </button>
                <button @click="nextPage"
                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Next
                </button>
            </div>
            
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium" x-text="currentPage * perPage - perPage + 1"></span>
                        -
                        <span class="font-medium" x-text="Math.min(currentPage * perPage, totalItems)"></span>
                        dari
                        <span class="font-medium" x-text="totalItems"></span>
                        data
                    </p>
                </div>
                <div>
                    <nav class="inline-flex -space-x-px rounded-md shadow-sm isolate">
                        <button @click="previousPage" 
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-2 py-2 text-gray-400 rounded-l-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }">
                            <span class="sr-only">Previous</span>
                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <template x-for="page in pages">
                            <button @click="currentPage = page" 
                                    aria-current="page"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                                    :class="page === currentPage 
                                        ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' 
                                        : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0'">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        
                        <button @click="nextPage" 
                                :disabled="currentPage === totalPages"
                                class="relative inline-flex items-center px-2 py-2 text-gray-400 rounded-r-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }">
                            <span class="sr-only">Next</span>
                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
</div>



<script>
    function transaksi (){
        return {
            currentPage: 1,
            perPage: 5,
            totalItems: {{ $data->count() }},
            init() {
                this.totalItems = this.transactions.length;
                console.log(this.transactions[0]?.pesanan);
            },
            get transactions() {
                return @json($data);
            },
            get totalPages() {
                return Math.ceil(this.totalItems / this.perPage);
            },
            get paginatedData() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.transactions.slice(start, end);
            },
            get pages() {
                return Array.from({length: this.totalPages}, (_, i) => i + 1);
            },
            formatDate(dateString) {
                const date = new Date(dateString);
                return new Intl.DateTimeFormat('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }).format(date);
            },
            numberFormat(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            },
            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },
            previousPage() {
                if (this.currentPage > 1) this.currentPage--;
            }
        };
    }
</script>

</div>
