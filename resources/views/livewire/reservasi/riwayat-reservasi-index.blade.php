<div wire:poll>


    
    <div class="overflow-x-auto">
        <table class="table table-zebra">
          <!-- head -->
          <thead>
            <tr>
                <th>#</th>
                <th class="text-sm cursor-pointer text-center">
                    Nomor Reservasi
                </th>
                <th class="text-sm cursor-pointer text-center">
                    Tamu
                </th>
                <th class="text-sm cursor-pointer text-center">
                    Tanggal Check In
                </th>
                <th class="text-sm cursor-pointer text-center">
                    Tanggal Check Out
                </th>
             
                <th class="text-sm cursor-pointer text-center">
                    Status Reservasi
                </th>
                
                <th class="text-sm cursor-pointer text-center">
                    Action
                </th>
                
            </tr>
          </thead>

          <tbody>
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $item->no_reservasi }}</td>
                <td class="text-center">{{ $item->tamu->nama }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_check_in)->format('d-M-y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_check_out)->format('d-M-y') }}</td>
                <td class="text-center">
                    @if ($item->status_reservasi == 'check_in')
                    Check In
                    @elseif ($item->status_reservasi == 'check_out')
                        Check Out
                    @else
                    {{ $item->status_reservasi }}
                @endif
                </td>
                <td class="text-center">
                    <button class="btn btn-primary btn-sm" wire:click="detail({{ $item->id }})">Detail</button>

                    @if (Auth::user()->hasRole('resepsionis'))
                                @if ($item->status_reservasi != 'batal')
                                <buttton class="btn btn-sm btn-primary" wire:click="INVOICE({{ $item->id }})">Invoice</buttton>
                            @endif
                            <button class="btn btn-neutral btn-sm" wire:click="delete({{ $item->id }})">Hapus</button>
                    @endif
                    
                </td>
            </tr>
            @empty
                <tr>
                    <td>Tidak ada Data</td>
                </tr>
            @endforelse


          </tbody>

        </table>
    </div>


    <div class="mt-3">
        {{ $data->links() }}
    </div>




    
    <div>
        <x-dialog-modal wire:model.live="showModalDelete"> 
            <x-slot name="title">
                Hapus Data Reservasi
            </x-slot>
        
            <x-slot name="content">
                <p>Apakah anda yakin ingin menghapus reservasi dengan ID: {{ $id }} dan dengan nomor reservasi {{ $no_reservasi }}</p>
            </x-slot>
        
            <x-slot name="footer">
                <div class="flex justify-end gap-3 mt-5">
                    <x-secondary-button @click="$wire.set('showModalDelete', false)" >
                        Batal
                     </x-secondary-button>
             
                     <button class="btn btn-primary ml-2"  @click="$wire.submitDelete()" >
                         Hapus
                     </button>
                </div>
               
            </x-slot>
        </x-dialog-modal>
    </div>


            
    <x-dialog wire:model="showModalDetail" maxWidth="2xl">
        <x-slot name="title">
            <div class="flex items-center gap-3 px-6 pt-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-900">Detail Reservasi #{{ $no_reservasi }}</h2>
            </div>
        </x-slot>
    
        <x-slot name="content">
            <div class="space-y-6 px-6 pb-4">
                <!-- Header Info -->
                <div class="bg-indigo-50 p-4 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white rounded-lg shadow-sm">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $namaTamu }}</h3>
                                <p class="text-sm text-gray-500">Informasi Tamu</p>
                                <p class="text-sm text-gray-500">No Tlpn : {{ $noTlpn }}</p>

                            </div>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                  {{ $status_reservasi == 'Check In' ? 'bg-green-100 text-green-900' : 
                                     ($status_reservasi == 'Check Out' ? 'bg-green-100 text-green-900' : 'bg-blue-100 text-blue-800') }}">
                            {{ $status_reservasi }}
                        </span>
                    </div>
                </div>
    
                <!-- Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Left Column - Reservation Details -->
                    <div class="col-span-2 space-y-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-500 mb-3">Detail Reservasi</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Durasi</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $jumlahHari }} Hari</span>
                                </div>

                              
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Check-in</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($tanggal_check_in)->translatedFormat('d F Y'); }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Check-out</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($tanggal_check_out)->translatedFormat('d F Y'); }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Jumlah Kamar</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $jumlahKamar }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Status Reservasi</span>
                                    </div>

                                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $status_reservasi == 'Check In' ? 'bg-green-100 text-green-900' : 
                                     ($status_reservasi == 'Check Out' ? 'bg-green-100 text-green-900' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $status_reservasi }}
                                        </span>
                                </div>


                            </div>
                        </div>


                        <div class="bg-white p-2 rounded-xl border border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-500 mb-4">Detail Kamar</h4>
                        
                            <div class="flex flex-wrap gap-4 justify-center">
                                @foreach ($pesanan as $item)
                                    <a class="bg-gray-100 flex-grow text-black border-l-8 border-green-500 rounded-md px-3 py-4 w-full md:w-5/12 lg:w-3/12">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item['no_kamar'] }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item['jenisKamar'] }}</p>
                                        
                                        <div class="text-sm font-thin text-gray-500 pt-2">
                                            <p>Harga Kamar: 
                                                <span class="text-gray-900">Rp {{ number_format($item['harga_kamar'], 0, ',', '.') }}</span>
                                            </p>
                                            
                                            @if ($item['persentase_diskon'] && $item['persentase_kenaikan_harga'])
                                                <p>Diskon: 
                                                    <span class="text-red-600">{{ '-'.$item['persentase_diskon'].'%' }}</span>
                                                </p>
                                                <p>Kenaikan Harga: 
                                                    <span class="text-orange-600">{{ '+'.$item['persentase_kenaikan_harga'].'%' }}</span>
                                                </p>
                                            @elseif ($item['persentase_diskon'])
                                                <p>Diskon: 
                                                    <span class="text-red-600">{{ '-'.$item['persentase_diskon'].'%' }}</span>
                                                </p>
                                            @elseif ($item['persentase_kenaikan_harga'])
                                                <p>Kenaikan Harga: 
                                                    <span class="text-orange-600">{{ '+'.$item['persentase_kenaikan_harga'].'%' }}</span>
                                                </p>
                                            @endif
                                        </div>
                        
                                        <!-- Info di kanan dan sejajar vertikal dan horizontal -->
                                        <div class="flex justify-between mt-4">
                                            <div class="flex items-center ml-auto text-sm font-thin text-gray-500 space-x-6">
                                                <div class="flex flex-col">
                                                    <p>Harga Akhir: 
                                                        <span class="text-gray-900">Rp {{ number_format($item['harga_akhir'], 0, ',', '.') }}</span>
                                                    </p>
                                                </div>
                        
                                                <div class="flex flex-col">
                                                    <p>Jumlah Malam: 
                                                        <span class="text-gray-900">{{ $item['jumlah_malam'] }}</span>
                                                    </p>
                                                </div>
                        
                                                <div class="flex flex-col">
                                                    <p>Subtotal: 
                                                        <span class="text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        

    
                    </div>
    
                    <!-- Right Column - Payment Summary -->
                    <div class="space-y-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-600 mb-3">Jumlah Pembayaran</h4>
                            <div class="space-y-4">
                        
                                <!-- Menampilkan Harga Awal -->
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-600">Harga Awal :</span>
                                    @foreach ($hargaDasarList as $noKamar => $harga)
                                        <div class="mt-1 text-sm text-gray-900">
                                            #{{ $loop->iteration }} Kamar: {{ $noKamar }} - Rp {{ number_format($harga, 0, ',', '.') }}
                                            @if ($item['persentase_diskon'] && $item['persentase_kenaikan_harga'])
                                            <p>Diskon: 
                                                <span class="text-red-600">{{ '-'.$item['persentase_diskon'].'%' }}</span>
                                            </p>
                                            <p>Kenaikan Harga: 
                                                <span class="text-orange-600">{{ '+'.$item['persentase_kenaikan_harga'].'%' }}</span>
                                            </p>
                                        @elseif ($item['persentase_diskon'])
                                            <p>Diskon: 
                                                <span class="text-red-600">{{ '-'.$item['persentase_diskon'].'%' }}</span>
                                            </p>
                                        @elseif ($item['persentase_kenaikan_harga'])
                                            <p>Kenaikan Harga: 
                                                <span class="text-orange-600">{{ '+'.$item['persentase_kenaikan_harga'].'%' }}</span>
                                            </p>
                                        @endif
                                        </div>
                                    @endforeach
                                </div>
                        
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Jumlah Hari : </span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $jumlahHari }}
                                    </span>
                                </div>

                                <!-- Menampilkan Harga Akhir -->
                                <div class="flex flex-col mt-4">
                                    <span class="text-sm text-gray-600">Harga Akhir :</span>
                                    @foreach ($hargaAkhirList as $noKamar => $hargaAkhir)
                                        <div class="mt-1 text-sm text-gray-900">
                                            #{{ $loop->iteration }} Kamar: {{ $noKamar }} - Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                                        </div>
                                    @endforeach
                                </div>

                                    <!-- Menampilkan Jumlah Kamar -->
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Jumlah Kamar : </span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $jumlahKamar }}
                                        </span>
                                    </div>
                            
                                    @if ($denda != "0.00")
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Denda</span>
                                            <span class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($denda, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                <!-- Total Pembayaran -->
                                <div class="pt-2 border-t border-gray-100">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-semibold text-gray-700">Total</span>
                                        <span class="text-sm font-semibold text-blue-600">
                                            Rp {{ number_format($total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                        
                            </div>
                        </div>
                        
                        
                        
                        @if ($pembayaran)
                            <div class="bg-white p-4 rounded-xl border border-gray-100">
                                <h4 class="text-sm font-semibold text-gray-500 mb-3">Detail Pembayaran</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Jumlah Pembayaran</span>
                                        <span class="text-sm font-medium text-green-600">
                                            Rp {{ number_format($jumlahPembayaran, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Kembalian</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($kembalian, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    @if($status_reservasi == 'check_out')
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Denda</span>
                                            <span class="text-sm font-medium text-red-600">
                                                Rp {{ number_format($denda, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="pt-2 border-t border-gray-100">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Transaksi. {{ $user ?? 'System' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                       

                    </div>
                </div>
    
                    <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800 mb-1">Keterangan</p>
                                <p class="text-sm text-yellow-700">{{ $keterangan ?? 'Tidak ada Keterangan' }}</p>
                            </div>
                        </div>
                    </div>

            </div>
        </x-slot>
    
        <x-slot name="footer">
            <div class="px-6 pb-4 flex justify-end">
                <button wire:click="$set('showModalDetail', false)" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Tutup
                </button>
            </div>
        </x-slot>
    </x-dialog>










    <x-dialog wire:model="showModalInvoice" maxWidth="2xl">
        <x-slot name="title">
            <div class="flex items-center gap-3 px-6 pt-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-900">Invoice #{{ $invoiceNumber }}</h2>
            </div>
        </x-slot>
    
        <x-slot name="content">
            <div class="space-y-6 px-6 pb-4">
                <!-- Header Info -->
                <div class="p-4 rounded-xl">
                    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center">
                                <img class="h-20 w-20 mr-2" src="{{ asset('images/logo_hotel.png') }}" alt="Logo" />
                                <div class="text-gray-700 font-semibold text-lg">Hotel Crown Pangandaran Syariah</div>
                            </div>
                            <div class="text-gray-700 text-right">
                                <div class="font-bold text-xl mb-2">INVOICE</div>
                                <div class="text-sm">Tanggal: {{ $invoiceDate }}</div>
                                <div class="text-sm">Invoice #: {{ $invoiceNumber }}</div>
                            </div>
                        </div>
                    
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <h3 class="font-bold text-gray-700">Hotel Crown Pangandaran Syariah</h3>
                                <p class="text-gray-600 text-sm">
                                    Jl. Pangandaran No. 10, Jawa Barat<br>
                                    Email: crownhotelpangandaran1@gmail.com<br>
                                    Telp: (0858) 05362620
                                </p>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-700">Kepada:</h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $namaTamu }}<br>
                                    {{ $alamatTamu }}, {{ $kotaTamu }}<br>
                                    {{ $emailTamu }}<br>
                                    No. Telepon: {{ $teleponTamu }}
                                </p>
                            </div>
                        </div>
                    
                        <table class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <th class="p-2 text-gray-700">Kamar</th>
                                    <th class="p-2 text-gray-700">Durasi</th>
                                    <th class="p-2 text-gray-700">Harga/Malam</th>
                                    <th class="p-2 text-gray-700">Total</th>
                                    <th class="p-2 text-gray-700">Status</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice as $item)
                                    <tr class="text-center">
                                        <td class="p-2 text-gray-700">{{ $item['jenisKamar'] }}</td>
                                        <td class="p-2 text-gray-700">{{ $item['jumlah_malam'] }} Malam</td>
                                        <td class="p-2 text-gray-700">Rp {{ number_format($item['harga_akhir'], 0, ',', '.') }}</td>
                                        <td class="p-2 text-gray-700">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        <td class="p-2 text-gray-700">{{ $status_reservasi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    



                        <div class="flex justify-between mt-6 gap-8">
                            <!-- Bagian Kiri: Informasi Kamar & Pembayaran -->
                            <div class="flex-1">
                                <table class="w-full">
                                    @if ($status_reservasi == "Check in")
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal w-1/2">Total Kamar:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">{{ $jumlahKamar }}</td>
                                        </tr>
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal">Jumlah Pembayaran:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($jumlahPembayaran, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal">Kembalian:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
                                        </tr>
                                    @elseif ($status_reservasi == "Check out")
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal w-1/2">Total Kamar:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">{{ $jumlahKamar}}</td>
                                        </tr>
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal">Jumlah Pembayaran:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($jumlahPembayaran, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal">Kembalian:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th class="p-2 text-gray-600 text-sm font-normal w-1/2">Total Kamar:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">{{ $jumlahKamar}}</td>
                                        </tr>
                                        
                                    @endif
                                </table>
                            </div>
                        
                            <!-- Garis Pemisah Vertikal -->
                            <div class="border-r border-gray-200 h-auto my-2"></div>
                        
                            <!-- Bagian Kanan: Total & Denda -->
                            <div class="flex-1">
                                <table class="w-full">
                                    @if ($status_reservasi == "Check in")
                                        <tr class="border-t border-gray-200">
                                            <th class="p-2 text-gray-900 font-bold pt-3">Total:</th>
                                            <td class="p-2 text-red-600 font-bold text-right pt-3">Rp {{ number_format($total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @elseif ($status_reservasi == "Check out")
                                        <tr>
                                            <th class="p-2 text-gray-900 font-bold pt-3">Total:</th>
                                            <td class="p-2 text-red-600 font-bold text-right pt-3">Rp {{ number_format($total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="border-t border-gray-200">
                                            <th class="p-2 text-gray-600 text-sm font-normal">Denda:</th>
                                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($denda, 0, ',', '.') }}</td>
                                        </tr>
                                    @else
                                        <tr class="border-t border-gray-200">
                                            <th class="p-2 text-gray-900 font-bold pt-3">Total:</th>
                                            <td class="p-2 text-red-600 font-bold text-right pt-3">Rp {{ number_format($total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    






                    
                    </div>

                </div>
    
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <div class="flex justify-end">
                <button wire:click="$set('showModalInvoice', false)" style="margin-right: 20px;"
                        class="px-3 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Tutup
                </button>

                <button wire:click="cetakInvoice()" 
                        class="px-3 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Cetak
                </button>
            </div>
        </x-slot>
    </x-dialog>







</div>
