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
                    @if ($item->status_reservasi != 'batal')
                        <button class="btn btn-primary btn-sm" wire:click="invoice({{ $item->id }})">Invoice</button>  
                    @endif

                    @if ($item->status_reservasi == 'dipesan')
                    <buttton class="btn btn-sm btn-primary" wire:click="checkIn({{ $item->id }})">Check In</buttton>
                    <buttton class="btn btn-sm btn-primary" wire:click="batal({{ $item->id }})" >Batalkan</buttton>
                    @elseif ($item->status_reservasi == 'check_in')
                        <buttton  class="btn btn-sm btn-primary" wire:click="checkOut({{ $item->id }})">Check Out</buttton>
                    @elseif ($item->status_reservasi == 'check_out')
                    <buttton class="btn btn-sm btn-primary" wire:click="selesai({{ $item->id }})">Selesaikan</buttton>
                    @endif
                    <button class="btn btn-neutral btn-sm" wire:click="delete({{ $item->id }})">Hapus</button>

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

                        <div>
                            <x-dialog-modal wire:model.live="showModalBatal"> 
                                <x-slot name="title">
                                    Batalkan Data Reservasi
                                </x-slot>
                            
                                <x-slot name="content">
                                    <p>Apakah anda yakin ingin membatalkan reservasi dengan ID: {{ $id }} dan dengan nomor reservasi {{ $no_reservasi }}</p>
                                </x-slot>
                            
                                <x-slot name="footer">
                                    <div class="flex justify-end gap-3 mt-5">
                                        <x-secondary-button @click="$wire.set('showModalBatal', false)" >
                                            Batal
                                         </x-secondary-button>
                                 
                                         <button class="btn btn-primary ml-2"  @click="$wire.submitBatal()" >
                                             Batalkan
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
                                                      {{ $status_reservasi == 'Selesai' ? 'bg-green-100 text-green-800' : 
                                                         ($status_reservasi == 'batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
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
                                                        {{ $status_reservasi == 'Selesai' ? 'bg-green-100 text-green-800' : 
                                                                        ($status_reservasi == 'batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
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
                                                
                                                        @if ($denda != null)
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
                                                                Rp {{ number_format($total_harga + $denda, 0, ',', '.') }}
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














                        <x-dialog wire:model="showModalCheckIn" submit="submitCheckIn"  maxWidth="2xl">
                            <x-slot name="title">
                                <div class="flex items-center gap-3 px-6 pt-4">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h2 class="text-xl font-bold text-gray-900">Check In Dan Pembayaran Reservasi #{{ $no_reservasi }}</h2>
                                </div>
                            </x-slot>
                        
                            <x-slot name="content">
                                <div class="space-y-6 px-6 pb-4">
                                
                        
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
                                                        {{ $status_reservasi == 'Selesai' ? 'bg-green-100 text-green-800' : 
                                                                        ($status_reservasi == 'batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
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
                                                <h4 class="text-sm font-semibold text-gray-500 mb-3">Jumlah Pembayaran</h4>
                                                <div class="space-y-4" x-data="kembalianComponent()" x-init="init()">
                                            
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
                                                
                                                        @if ($pembayaran)
                                                            <div class="flex justify-between">
                                                                <span class="text-sm text-gray-600">Denda</span>
                                                                <span class="text-sm font-medium text-gray-900">
                                                                    Rp {{ number_format($denda, 0, ',', '.') }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <div class="grid grid-cols-1">
                                                            <div>
                                                                <label class="text-sm font-medium text-gray-600 text-gray-600">Jumlah Pembayaran:</label>
                                                                <input type="text" id="jumlahPembayaran" wire:model.live="jumlahPembayaran" required x-model="jumlahPembayaran" @change="updateKembalian()" class="input input-bordered w-full text-md btn-md" placeholder="Masukkan Jumlah Pembayaran">
                                                            </div>
                                                       
                                                        </div>
                                                        
                                                    <!-- Total Pembayaran -->
                                                    <div class="pt-2 border-t border-gray-300">
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-semibold text-gray-700">Total</span>
                                                            <span class="text-sm font-semibold text-blue-600">
                                                                Rp {{ number_format($total_harga + $denda, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                        <div class="divider"></div>
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-semibold text-gray-700">Kembalian</span>
                                                            <span class="text-sm font-semibold text-red-600">
                                                                <span x-text="formatRupiah(kembalian)"></span>
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-end gap-3 mt-10">
                                                            <button type="button" wire:click="$set('showModalCheckIn', false)" 
                                                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 
                                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                                            Batal
                                                            </button>
                                                            <button type="submit"  class="px-4 py-2 bg-blue-800 text-white rounded">Bayar</button>
                                                        </div>
                                                    </div>
                                            
                                                </div>
                                            </div>
                                            
                                            
                                            
                                    

                                           

                                        </div>
                                    </div>
                        

                                </div>
                            </x-slot>
                        

                        </x-dialog>




                        <x-dialog wire:model="showModalCheckOut" submit="submitCheckOut"  maxWidth="2xl">
                            <x-slot name="title">
                                <div class="flex items-center gap-3 px-6 pt-4">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h2 class="text-xl font-bold text-gray-900">Check Out Dan Penyelesaian Reservasi #{{ $no_reservasi }}</h2>
                                </div>
                            </x-slot>
                        
                            <x-slot name="content">
                                <div class="space-y-6 px-6 pb-4">
                                
                        
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
                                                        {{ $status_reservasi == 'Selesai' ? 'bg-green-100 text-green-800' : 
                                                                        ($status_reservasi == 'batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
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
                                                <h4 class="text-sm font-semibold text-gray-500 mb-3">Jumlah Pembayaran</h4>
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
                                                
                            

                                                
                                                        
                                                    <!-- Total Pembayaran -->
                                                    <div class="pt-2 border-t border-gray-300">
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-semibold text-gray-700">Total</span>
                                                            <span class="text-sm font-semibold text-blue-600">
                                                                Rp {{ number_format($total_harga + $denda, 0, ',', '.') }}
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
                                            
                                                </div>
                                            </div>

                                        @endif
                                            
                                            
                                    

                                           

                                        </div>
                                    </div>
                        

                                </div>
                            </x-slot>


                            <x-slot name="footer">
                                <div class="px-6 pb-4 flex justify-end">
                                    <button wire:click="$set('showModalCheckOut', false)" 
                                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 
                                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Tutup
                                    </button>
                                </div>
                            </x-slot>
                        

                        </x-dialog>


                        



    <div class="mt-3">
        {{ $data->onEachSide(1)->links() }}
    </div> 

    <script>
        document.getElementById("jumlahPembayaran").addEventListener("input", function () {
            let angka = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
            let formatRupiah = new Intl.NumberFormat("id-ID").format(angka); // Format ke Rupiah
            this.value = formatRupiah ? "Rp " + formatRupiah : "";
        });
    
        function kembalianComponent() {
            return {
                totalHarga: @entangle('total_harga'),
                jumlahPembayaran: @entangle('jumlahPembayaran'),
                kembalian: 0,
    
                formatRupiah(angka) {
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 0
                    }).format(angka || 0);
                },
    
                updateKembalian() {
                    let pembayaran = parseInt(this.jumlahPembayaran?.replace(/[^\d]/g, '') || 0);
                    this.kembalian = pembayaran - (parseInt(this.totalHarga) || 0);
                },
    
                init() {
                    this.$watch('jumlahPembayaran', () => this.updateKembalian());
                    this.$watch('totalHarga', () => this.updateKembalian());
                }
            };
        }
    
    </script>
</div>