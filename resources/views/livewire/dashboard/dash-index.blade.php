<div>

<div class="py-6">
    {{-- <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Dashboard Resepsioniss - {{ now()->translatedFormat('l, d F Y') }}
    </h2>
 --}}
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-3xl m-4 font-semibold text-gray-100">
        Dashboard Resepsioniss - {{ now()->translatedFormat('l, d F Y') }}
    </h2>

        <!-- Grid Statistik -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- Kamar Tersedia -->
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kamar Tersedia</p>
                        <p class="mt-2 text-3xl font-semibold text-emerald-600">{{ $kamarTersedia }}</p>
                    </div>
                    <x-icons.bed class="w-12 h-12 text-emerald-100" />
                </div>
            </div>

            <!-- Reservasi Hari Ini -->
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Reservasi Hari Ini</p>
                        <p class="mt-2 text-3xl font-semibold text-blue-600">{{ $reservasiHariIni }}</p>
                    </div>
                    <x-icons.calendar class="w-12 h-12 text-blue-100" />
                </div>
            </div>

            <!-- Tamu Check-in -->
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tamu Akan Check-in</p>
                        <p class="mt-2 text-3xl font-semibold text-purple-600">{{ $tamuCheckIn }}</p>
                    </div>
                    <x-icons.users class="w-12 h-12 text-purple-100" />
                </div>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                        <p class="mt-2 text-2xl font-semibold text-rose-600">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
                    </div>
                    <x-icons.cash class="w-12 h-12 text-rose-100" />
                </div>
            </div>
        </div>

        <!-- Grid Konten Utama -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Reservasi Terbaru -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Reservasi Hari ini</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">No. Reservasi</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">Nama Tamu</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">No Kamar</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">Check-in</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- @dd($reservasiTerbaru) --}}
                                @foreach($reservasiTerbaru as $reservasi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $reservasi->no_reservasi }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $reservasi->tamu->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @foreach($reservasi->pesanan as $pesanan)
                                        {{ $pesanan->kamar->jenisKamar->tipe_kamar }} - {{ $pesanan->kamar->jenisKamar->jenis_ranjang }}<br>
                                    @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($reservasi->tanggal_check_in)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $reservasi->status_reservasi === 'dipesan' ? 'bg-blue-100 text-blue-800' : 
                                               ($reservasi->status_reservasi === 'check_in' ? 'bg-green-100 text-green-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $reservasi->status_reservasi)) }}

                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terkini -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terkini</h3>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Kamar dalam Perbaikan -->
                    <div class="flex items-start p-3 bg-orange-50 rounded-lg">
                        <x-icons.tools class="flex-shrink-0 w-5 h-5 mt-1 text-orange-600" />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-orange-800">{{ $kamarPerbaikan }} Kamar</p>
                            <p class="text-xs text-orange-700">Sedang dalam perbaikan</p>
                        </div>
                    </div>

                    <!-- Diskon Aktif -->
                    <div class="flex items-start p-3 bg-purple-50 rounded-lg">
                        <x-icons.tag class="flex-shrink-0 w-5 h-5 mt-1 text-purple-600" />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-800">{{ $totalDiskonAktif }} Diskon</p>
                            <p class="text-xs text-purple-700">Promo aktif saat ini</p>
                        </div>
                    </div>

                    <!-- Keluhan Aktif -->
                    <div class="flex items-start p-3 bg-red-50 rounded-lg">
                        <x-icons.alert-circle class="flex-shrink-0 w-5 h-5 mt-1 text-red-600" />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ $keluhanAktif->count() }} Keluhan</p>
                            <p class="text-xs text-red-700">Memerlukan penanganan</p>
                        </div>
                    </div>

                    <!-- Tingkat Okupansi -->
                    {{-- <div class="flex items-start p-3 bg-blue-50 rounded-lg">
                        <x-icons.chart-bar class="flex-shrink-0 w-5 h-5 mt-1 text-blue-600" />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">{{ $occupancyRate }}%</p>
                            <p class="text-xs text-blue-700">Tingkat okupansi kamar hari ini</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>








</div>