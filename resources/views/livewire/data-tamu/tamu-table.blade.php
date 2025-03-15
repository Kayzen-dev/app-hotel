<div wire:poll>
        
    {{-- @dd($data) --}}

    <h2 class="text-2xl m-5 font-semibold text-gray-700 dark:text-white">Data Tamus</h2>


    <div class="overflow-x-auto">
        <table class="table table-zebra">
          <!-- head -->
          <thead>
            <tr>
                <th>#</th>
                <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('nama')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'nama'" /> Nama Tamu
                </th>
                <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('no_tlpn')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'no_tlpn'" /> Nomor Telepon
                </th>
                <th class="text-sm cursor-pointer text-center">
                    Jumlah Reservasi
                </th>

            <th class="text-sm cursor-pointer text-center">Action</th>

            </tr>

            </tr>

          </thead>

          <tbody>
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $item->nama ?? 'Data Kosong' }}</td>
                <td class="text-center">{{ $item->no_tlpn ?? 'Data Kosong' }}</td>
                <td  class="text-center" >{{ $item->reservasi_count }}</td>

                <td  class="text-center">

                    <x-button @click="$dispatch('dispatch-tamu-table-edit', { id: '{{ $item->id }}' })"
                        type="button" class="text-sm">
                        @if (in_array('no-data', [$item->kota, $item->alamat, $item->no_identitas, $item->email]))
                            Lengkapi Data Tamu
                            @else
                            Edit
                        @endif
                    </x-button>

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


                        {{-- CheckIn --}}
                        <div>
                                <x-dialog wire:model.live="showModalCheckIn" submit="pembayaran">
                                    {{-- <x-slot name="title">
                                        Check In dan Pembayaran Kamar
                                    </x-slot> --}}
                            
                                    <x-slot name="content">
                                        <div>
                                            <!-- Layout Ringkasan dan Pembayaran -->
                                            <div class="flex flex-col md:flex-row gap-6 mt-3">
                                                <!-- Bagian Ringkasan Harga -->
                                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-2/3">
                                                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Detail Harga Dan Pembayaran</h2>
                                                    <ul class="space-y-3 text-lg font-medium text-gray-700 dark:text-gray-300">
                                                        <li class="flex justify-between items-center">
                                                            <span class="font-semibold text-gray-800 dark:text-white">
                                                            Tanggal Check In : {{ \Carbon\Carbon::parse($tanggal_check_in)->format('d-M-y') }}
                                                            </span>
                                                            <span class="font-semibold text-gray-800 dark:text-white">
                                                            Tanggal Check Out :
                                                                {{ \Carbon\Carbon::parse($tanggal_check_out)->format('d-M-y') }}
                                                            </span>
                                                        </li>
                                                        <li class="flex justify-between items-center">
                                                            Nomor Reservasi :
                                                            <span class="font-semibold text-gray-800 dark:text-white">{{ $no_reservasi }}</span>
                                                        </li>
                                                        
                                                        <li class="flex justify-between items-center">
                                                            Nomor Kamar :
                                                            <span class="font-semibold text-gray-800 dark:text-white">{{ $no_kamar }}</span>
                                                        </li>
                                                        <li class="flex justify-between items-center">
                                                            Tipe Kamar :
                                                            <span class="font-semibold text-gray-800 dark:text-white">{{ $tipe_kamar }}</span>
                                                        </li>
                                                        <li class="flex justify-between items-center">
                                                            Jenis Ranjang :
                                                            <span class="font-semibold text-gray-800 dark:text-white">{{ $jenis_ranjang }}</span>
                                                        </li>
                                                        
                                                        <div class="divider divider-primary"></div>

                                                        <li class="flex justify-between items-center">
                                                            Harga Kamar Permalam :
                                                            <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($hargaDasar, 0, ',', '.') }}</span>
                                                        </li>
                                                    
                                                        @if ($hargaKhusus)
                                                            <li class="flex justify-between items-center text-red-600">
                                                                Kenaikan Harga Kamar :
                                                                <span class="font-semibold">+Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                                                                    <span class="text-sm text-gray-500">(+{{ round($persentase_kenaikan_harga) }}%)</span>
                                                                </span>
                                                            </li>
                                                        @endif
                                                        
                                                        @if ($diskon)
                                                            <li class="flex justify-between items-center font-semibold text-green-600">
                                                                Harga Kamar Akhir Setelah Diskon :
                                                                <span class="font-semibold">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                                                    <span class="text-sm text-gray-500">(-{{ round($persentase) }}%)</span>
                                                                </span>
                                                            </li>
                                                        @endif
                                                        <li class="flex justify-between items-center">
                                                            Jumlah Hari : <span>{{ $jumlahHari }}</span>
                                                        </li>
                                                        <li class="flex justify-between items-center text-xl font-bold text-gray-800 dark:text-white">
                                                            <span>Total yang Harus Dibayar :</span>
                                                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- Bagian Pembayaran -->
                                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-1/3" x-data="kembalianComponent()" x-init="init()">
                                                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Pembayaran</h2>
                                                    <div class="grid grid-cols-1 gap-6">
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Pembayaran:</label>
                                                            <input type="text" id="jumlahPembayaran" wire:model.live="jumlahPembayaran" required x-model="jumlahPembayaran" @change="updateKembalian()" class="input input-bordered w-full text-lg" placeholder="Masukkan Jumlah Pembayaran">
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Harga Kamar:</label>
                                                            <div class="mt-2 text-lg font-semibold text-gray-800 dark:text-white">
                                                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Kembalian:</label>
                                                            <div class="mt-2 text-lg font-semibold text-red-500">
                                                                <span x-text="formatRupiah(kembalian)"></span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="flex justify-end gap-3 mt-20">
                                                        <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded">Bayar</button>
                                                        <button type="button" wire:click="$set('showModalCheckIn', false)" class="ml-2 px-4 py-2 bg-gray-700 text-white rounded">Batal</button>
                                                    </div>
                                                </div>


                                            

                                            </div>
                                        </div>
                                    </x-slot>

                                </x-dialog>
                        </div> 




                        {{-- CheckOUT --}}
                            <div>
                                <x-dialog wire:model.live="showModalCheckOut" submit="submitCheckOut">
                                
                                    <x-slot name="content">
                                        <div class="flex flex-col md:flex-row gap-6 mt-3">

                                            <div class="flex flex-col justify-between w-full gap-4">
                                                        <!-- Baris 1 -->
                                                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg h-2/2">
                                                            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Check Out Reservasi </h2>
                                                            <ul class="space-y-3 text-lg font-medium text-gray-700 dark:text-gray-300">
                                                                <li class="flex justify-between items-center">
                                                                    <span class="font-semibold text-gray-800 dark:text-white">
                                                                    Tanggal Check In : {{ \Carbon\Carbon::parse($tanggal_check_in)->format('d-M-y') }}
                                                                    </span>
                                                                    <span class="font-semibold text-gray-800 dark:text-white">
                                                                    Tanggal Check Out :
                                                                        {{ \Carbon\Carbon::parse($tanggal_check_out)->format('d-M-y') }}
                                                                    </span>
                                                                </li>
                                                                <li class="flex justify-between items-center">
                                                                    Nomor Reservasi :
                                                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $no_reservasi }}</span>
                                                                </li>
                                                                <li class="flex justify-between items-center">
                                                                    Nomor Kamar :
                                                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $no_kamar }}</span>
                                                                </li>
                                                                
                                                                <li class="flex justify-between items-center">
                                                                    Tipe Kamar :
                                                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $tipe_kamar }}</span>
                                                                </li>
                                                                <li class="flex justify-between items-center">
                                                                    Jenis Ranjang :
                                                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $jenis_ranjang }}</span>
                                                                </li>
                                                            </ul>

                                                            <li class="flex justify-between items-center text-xl font-bold text-gray-800 dark:text-white">
                                                                <span>Total Kamar :</span>
                                                                <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                                            </li>




                                                        </div>
                                    
                                                        <!-- Baris 2 -->
                                                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg h-1/2">
                                                            <ul class="space-y-3 text-lg font-medium text-gray-700 dark:text-gray-300">

                                                               

                                                                <li class="flex justify-between items-center text-xl font-bold text-gray-800 dark:text-white">
                                                                    <span>Total yang Harus Dibayar :</span>
                                                                    <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                                                </li>
                                                            </ul>


                                                            <div class="flex justify-end justify-between gap-3 mt-10">
                                                                <label for="id_denda" class="label text-xl font-bold">Denda (Opsional)</label>
                                                                <div>
                                                                    <input type="text" id="denda" wire:model.live="denda" required class="input  input-bordered w-full text-lg" placeholder="Masukkan Dendan (Optional)">
                                                                    {{-- <input type="text" id="ss" wire:model.live="test" required class="input  input-bordered w-full text-lg" placeholder="Masukkan Dendan (Optional)"> --}}
                                                                </div>
                                                            </div>
                                                            <div class="flex justify-end gap-3 mt-5">
                                                                <div>
                                                                    <button type="submit" class="px-4 py-2 btn btn-primary text-white rounded">Check Out</button>
                                                                    <button type="button" wire:click="$set('showModalCheckOut', false)" class="ml-2 px-4 py-2 bg-gray-700 text-white rounded">Batal</button>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    
                                            </div>
                            
                                        </div>
                                    </x-slot>
                            
                                </x-dialog>
                            </div>











                            <div>
                                <x-dialog wire:model.live="showModalInvoice" submit="cetakInvoice">
                                    <x-slot name="content">
                                        <div class="max-w-3xl mx-auto bg-white p-6 shadow-lg rounded-lg">
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
                                                        JL Kidang Pananjung No.88 Pangandaran, Jawa Barat<br>
                                                        Email: crownhotelpangandaran1@gmail.com<br>
                                                        Telp:  (0858) 05362620
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
                                                        <th class="p-2 text-sm text-center text-gray-700">Kamar</th>
                                                        <th class="p-2 text-sm text-center text-gray-700">No. Kamar</th>
                                                        <th class="p-2 text-sm text-center text-gray-700">Durasi</th>
                                                        <th class="p-2 text-sm text-center text-gray-700">Harga/Malam</th>
                                                        <th class="p-2 text-sm text-center text-gray-700">Total</th>
                                                        <th class="p-2 text-sm text-center text-gray-700">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($reservasi as $item)
                                                    <tr class="text-center">
                                                        <td class="p-2 text-sm text-center text-gray-700">{{ $item['tipe_kamar'] }} - {{ $item['jenis_ranjang'] }}</td>
                                                        <td class="p-2 text-sm text-center text-gray-700">{{ $item['no_kamar'] }}</td>
                                                        <td class="p-2 text-sm text-center text-gray-700">{{ $item['durasi'] }} Malam</td>
                                                        <td class="p-2 text-sm text-center text-gray-700">Rp{{ number_format($item['harga_kamar'], 0, ',', '.') }}</td>
                                                        <td class="p-2 text-sm text-center text-gray-700">Rp{{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                                                        <td class="p-2 text-sm text-center text-gray-700">
                                                            @if ($item['status'] == 'Check_in')
                                                                    Check In
                                                                @elseif ($item['status'] == 'Check_out')
                                                                    Check Out
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        
                                            <div class="flex justify-end mt-6">
                                                <table class="w-1/2 text-right">
                                                    <tr>
                                                        <th class="p-2 text-gray-700">Subtotal:</th>
                                                        <td class="p-2 text-gray-900 font-bold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <th class="p-2 text-gray-900 text-lg">Total:</th>
                                                        <td class="p-2 text-red-600 text-lg font-bold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
                                                        {{-- <td class="p-2 text-red-600 text-lg font-bold">Rp{{ number_format($totalHarga * 1.1, 0, ',', '.') }}</td> --}}
                                                    </tr>
                                                </table>    
                                            </div>
                                        
                                        </div>
                                    </x-slot>
                            
                                    <x-slot name="footer">
                                        <button type="submit" class="px-4 py-2 btn btn-primary text-white rounded">Cetak Invoice</button>
                                        <button type="button" wire:click="$set('showModalInvoice', false)" class="ml-2 px-4 py-2 bg-gray-700 text-white rounded">Batal</button>
                                    </x-slot>
                                </x-dialog>
                            </div>
                            
                            
















                        {{-- Batal --}}
                            <div>

                                <x-dialog-modal wire:model.live="showModalBatal"> 
                                    <x-slot name="title">
                                        Batalkan Reservasi 
                                    </x-slot>
                                
                                    <x-slot name="content">
                                        <p>Apakah anda yakin ingin  membatalkan reservasi dengan ID: {{ $idRes }} dan dengan nomor reservasi {{ $no_reservasi }}</p>
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



                            {{-- Selesai --}}
                            <div>

                                <x-dialog-modal wire:model.live="showModalSelesai"> 
                                    <x-slot name="title">
                                        Selesaikan Reservasi
                                    </x-slot>
                                
                                    <x-slot name="content">
                                        <p>Apakah anda yakin ingin menyelesaikan reservasi dengan ID: {{ $idRes }} dan dengan nomor reservasi {{ $no_reservasi }}</p>
                                    </x-slot>
                                
                                 
                                    <x-slot name="footer">
                                        <div class="flex justify-end gap-3 mt-5">
                                            <x-secondary-button @click="$wire.set('showModalSelesai', false)" >
                                                Batal
                                             </x-secondary-button>
                                     
                                             <button class="btn btn-primary ml-2"  @click="$wire.submitSelesai()" >
                                                 Selesai
                                             </button>
                                        </div>
                                       
                                    </x-slot>
                                </x-dialog-modal>
                            
                            </div>

    <div class="mt-3">
        {{ $data->onEachSide(1)->links() }}
    </div> 

    {{-- <br><br><br><br> --}}
</div>


<script>
    document.getElementById("jumlahPembayaran").addEventListener("input", function () {
        let angka = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
        let formatRupiah = new Intl.NumberFormat("id-ID").format(angka); // Format ke Rupiah
        this.value = formatRupiah ? "Rp " + formatRupiah : "";
    });

    document.getElementById("denda").addEventListener("input", function () {
        let angka = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
        let formatRupiah = new Intl.NumberFormat("id-ID").format(angka); // Format ke Rupiah
        this.value = formatRupiah ? "Rp " + formatRupiah : "";
    });



</script>









<script>
    function kembalianComponent() {
        return {
            totalHarga: @entangle('totalHarga'),
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
                this.kembalian = pembayaran - (this.totalHarga || 0);
            },

            init() {
                this.$watch('jumlahPembayaran', () => this.updateKembalian());
                this.$watch('totalHarga', () => this.updateKembalian());
            }
        };
    }
</script>