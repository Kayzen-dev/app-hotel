<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="kamarData()">
   

<!-- Error Message -->
<div 
        x-show="errorMessage"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-6"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="p-4 mb-4 flex items-center bg-gradient-to-r from-red-50 to-red-100 border-l-[3px] border-red-500 rounded-lg shadow-lg shadow-red-100/50 hover:shadow-red-200/40 transition-shadow"
        role="alert"
        >
        <div class="mr-3 shrink-0">
            <svg class="w-6 h-6 text-red-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="text-red-800 pr-4">
            <p class="font-semibold text-sm mb-1">Peringatan!</p>
            <p class="text-sm leading-tight" x-text="errorMessage"></p>
        </div>
        <button 
            @click="errorMessage = ''" 
            class="ml-auto text-red-600 hover:text-red-800 transition-colors p-1 -m-1 hover:bg-red-100 rounded-full"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        </div>

        <!-- Success Message -->
        <div 
        x-show="isSuccess"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-3"
        class="p-4 mb-4 bg-gradient-to-br from-green-50 to-emerald-50 border-l-[3px] border-emerald-500 rounded-lg shadow-lg shadow-emerald-100/50 hover:shadow-emerald-200/40 transition-shadow"
        >
        <div class="flex items-start">
            <div class="mr-3 shrink-0 p-1.5 bg-emerald-100 rounded-full">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            </div>
            <div class="flex-1 pr-4">
            <p class="font-semibold text-sm text-emerald-800 mb-1">Informasi Kamar</p>
            <p class="text-sm text-emerald-700 leading-tight" x-text="ketersediaan.message"></p>
            </div>
            <button 
            @click="isSuccess = false" 
            class="text-emerald-600 hover:text-emerald-800 transition-colors p-1 -m-1 hover:bg-emerald-100 rounded-full"
            >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
            </button>
        </div>
        
        {{-- <div class="mt-3 pl-9 grid grid-cols-3 gap-y-2 gap-x-4 text-sm">
            <div class="font-medium text-emerald-700">Total Harga:</div>
            <div class="col-span-2 font-semibold text-emerald-800">Rp <span x-text="formatRupiah(totalHarga)"></span></div>
            
            <div class="font-medium text-emerald-700">Jumlah Hari:</div>
            <div class="col-span-2 text-emerald-800"><span x-text="jumlahHari"></span> Hari</div>
            
            <div class="font-medium text-emerald-700">Harga per Malam:</div>
            <div class="col-span-2 text-emerald-800">Rp <span x-text="formatRupiah(hargaPerMalam)"></span></div>
        </div> --}}

        <!-- Daftar nomor kamar -->
        {{-- <div class="mt-3 pl-9">
            <div class="font-medium text-emerald-700 mb-2">Kamar Tersedia:</div>
            <ul class="grid grid-cols-2 gap-x-4 gap-y-2">
                <template x-for="(kamar, index) in no_kamar" :key="index">
                    <li class="text-emerald-800">
                        <span x-text="index + 1"></span>. <span x-text="kamar"></span>
                    </li>
                </template>
            </ul>
        </div> --}}

</div>

    <form wire:submit.prevent="save" novalidate>

        <!-- Ketersediaan Kamar -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Ketersediaan & Informasi Kamar</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                <!-- Tanggal Check-In -->
                <div>
                    <label for="tanggal_check_in" class="label text-sm font-medium text-gray-600 dark:text-gray-300">Tanggal Check In</label>
                    <input type="date" wire:model="tanggal_check_in" id="tanggal_check_in" class="input input-bordered w-full" required x-model="tanggal_check_in" @change="validateTanggal()">
                </div>

                <!-- Tanggal Check-Out -->
                <div>
                    <label for="tanggal_check_out" class="label text-sm font-medium text-gray-600 dark:text-gray-300">Tanggal Check Out</label>
                    <input type="date" wire:model="tanggal_check_out" id="tanggal_check_out" :disabled="!tanggal_check_in" class="input input-bordered w-full" required x-model="tanggal_check_out" @change="validateTanggal()">
                </div>




                <!-- Jenis Kamar -->
                <div>
                    <label class="label text-sm font-medium text-gray-600 dark:text-gray-300">Jenis Kamar</label>
                    <select x-model="selectedJenisKamar" @change="getKetersediaanKamar()" class="input input-bordered w-full" wire:model="id_jenis_kamar" required :disabled="!tanggal_check_in || !tanggal_check_out">
                        <option value="">-- Pilih Jenis Kamar --</option>
                        <template x-for="jenis in jenisKamarList" :key="jenis.id">
                            <option :value="jenis.id" x-text="`${jenis.tipe_kamar} - ${jenis.jenis_ranjang}`"></option>
                        </template>
                    </select>
                </div>



                <div>
                    <label class="label text-sm font-medium text-gray-600 dark:text-gray-300" for="tersedia">Jumlah Kamar Tersedia</label>
                    <input type="number" id="tersedia" readonly :disabled="!selectedJenisKamar" class="input input-bordered w-full" required x-model="jumlahTersedia">
                </div>



                {{-- Jumlah Kamar --}}
                <div>
                    <label class="label text-sm font-medium text-gray-600 dark:text-gray-300" for="jumlahKamar">Jumlah Kamar Pesanan</label>
                    <input type="number" wire:model="jumlahKamar" id="jumlahKamar" :disabled="!selectedJenisKamar" class="input input-bordered w-full" required x-model="jumlahKamar">
                </div>
              



                
            </div>
        </div>


        <!-- Layout Ringkasan dan Pembayaran -->
        <div class="flex flex-col md:flex-row gap-6 mt-3">

                <!-- Bagian Ringkasan Harga (Kiri) -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-2/3">
                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Detail Pesanan</h2>
                    <div class="space-y-4">
                        <ul class="space-y-3 text-lg font-medium text-gray-700 dark:text-gray-300">

                            <div class="space-y-4">
                                <li class="flex justify-between items-center text-lg font-medium">
                                    <span class="text-gray-800 dark:text-white">Harga Kamar Permalam:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white flex flex-col items-end">
                                        <span>Rp <span x-text="formatRupiah(hargaPerMalam)"></span></span>
                                        <div class="mt-2 space-x-2 text-sm flex justify-end">
                                            <!-- Display Price Increase if exists -->
                                            <span x-show="persentase_kenaikan_harga" class="text-red-600">
                                                (Kenaikan Harga +<span x-text="round(persentase_kenaikan_harga)"></span>%)
                                            </span>
                                            <!-- Display Discount if exists -->
                                            <span x-show="persentase_diskon" class="text-green-600">
                                                (Diskon -<span x-text="round(persentase_diskon)"></span>%)
                                            </span>
                                        </div>
                                    </span>
                                </li>
                            </div>
                            
                            












                            <!-- Jumlah Hari -->
                            <li class="flex justify-between items-center">
                                Jumlah Hari: <span x-text="jumlahHari"></span>
                            </li>

                            {{-- jumlah Kamar --}}
                            <li class="flex justify-between items-center">
                                Jumlah Kamar : <span x-text="jumlahKamar"></span>
                            </li>

                            <!-- Total yang Harus Dibayar -->
                            <li class="flex justify-between items-center text-xl font-bold text-gray-800 dark:text-white">
                                <span>Total yang Harus Dibayar:</span>
                                <span>Rp <span x-text="formatRupiah(totalHarga)"></span></span>
                            </li>
                            
                        </ul>
                    </div>
                </div>



                <!-- Bagian Pembayaran (Kanan) -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-1/3">
                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Detail Tamu</h2>
                    <div class="space-y-4">
                            <div class="grid grid-cols-1">
                                                <div>
                                                            <label for="tamu" class="label text-sm font-medium text-gray-600 dark:text-gray-300">Nama Lengkap Tamu:</label>
                                                            <div>
                                                                <label class="label text-sm font-medium text-gray-600 dark:text-gray-300">Pilih Data Tamu</label>
                                                                <select wire:model="idTamu" x-model="selectedTamu" required class="input input-bordered w-full">
                                                                    <option value="">Pilih data Tamu</option>
                                                                    <template x-for="tamu in tamus" :key="tamu.id">
                                                                        <option :value="tamu.id" x-text="`${tamu.nama}`"></option>
                                                                    </template>
                                                                </select>
                                                            </div>
                                                </div>

                                                <div class="divider divider-primary">Atau</div>
                                                <div class="grid grid-cols-1 ">
                                                                <div>
                                                                        <label for="nama" class="label text-sm font-medium text-gray-600 dark:text-gray-300">Nama Lengkap Tamu:</label>
                                                                        <input 
                                                                                type="text" 
                                                                                id="nama"
                                                                                wire:model="nama"
                                                                                class="input input-bordered w-full text-lg" 
                                                                                placeholder="Masukkan Nama Tamu" 
                                                                                required
                                                                                :disabled="selectedTamu != null"
                                                                            >
                                                                </div>
                                                </div>

                                                {{-- Tamu --}}
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                        <div>
                                                            <label for="no_tlpn"  class="label text-sm font-medium text-gray-600 dark:text-gray-300">Nomor Telepon Tamu</label>
                                                            <input type="number" :disabled="selectedTamu != null"  id="no_tlpn" wire:model="no_tlpn" class="input input-bordered w-full" required>
                                                        </div>

                                                        <div>
                                                            <label for="total" class="label text-sm font-medium text-gray-600 dark:text-gray-300">Total Harga Kamar:</label>
                                                            <input type="hidden" id="total" x-model="totalHarga" class="input input-bordered w-full">
                                                            <div class="mt-2 text-lg font-semibold text-gray-800 dark:text-white">
                                                                Rp <span x-text="formatRupiah(totalHarga)"></span>
                                                            </div>
                                                        </div>
                                                </div>


                                                <!-- Tombol Simpan -->
                                                <div class="flex justify-end gap-4 mt-6" x-show="isSuccess">
                                                    <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                                                        Simpan
                                                    </x-btn-accent>
                                                </div>


                            </div>
                    </div>
                </div>


        </div>

    </form>





    <script>
        function kamarData() {
            return {
                // State Properties
                selectedJenisKamar: '',
                jumlahKamar: @entangle('jumlahKamar'),
                selectedTamu: @entangle('idTamu'),
                jumlahTersedia: @entangle('jumlahTersedia'),
                tanggal_check_in: @entangle('tanggal_check_in'),
                tanggal_check_out: @entangle('tanggal_check_out'),
                hargaPerMalam: @entangle('hargaPerMalam'),
                id_diskon: @entangle('id_diskon'),
                persentase_diskon: @entangle('persentase_diskon'),
                id_harga: @entangle('id_harga'),
                persentase_kenaikan_harga: @entangle('persentase_kenaikan_harga'),
                totalHarga: @entangle('totalHarga'),
                jumlahHari: @entangle('jumlahHari'),
                reset: @entangle('reset'),
                jenisKamarList: [],
                tamus: [],
                no_kamar: [],
                ketersediaan: {},
                errorMessage: '',
                isSuccess: @entangle('isSuccess'), // Tambahkan ini
    
                // Inisialisasi
                async init() {
                    console.log('Init called');
                    await this.fetchData();
                    this.setupWatchers();
                    this.resetForm();
                    console.log('Reset:', this.reset);
                    console.log('Tamu:', this.selectedTamu);
                    console.log('in:', this.tanggal_check_in);
                    console.log('out:', this.tanggal_check_out);
                    console.log('Succ:', this.isSuccess);
                },
    
                // Fetch Data
                async fetchData() {
                    try {
                        const [jenisKamarRes, tamuRes] = await Promise.all([
                            fetch('/resepsionis/jenis-kamar'),
                            fetch('/resepsionis/data-tamu')
                        ]);
                        
                        this.jenisKamarList = await jenisKamarRes.json();
                        this.tamus = await tamuRes.json();
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        this.errorMessage = 'Gagal memuat data awal';
                    }
                },
    
                // Watchers
                setupWatchers() {
                    this.$watch('selectedJenisKamar', () => this.validateAndCalculate());
                    this.$watch('tanggal_check_in', () => this.validateAndCalculate());
                    this.$watch('tanggal_check_out', () => this.validateAndCalculate());
                    this.$watch('jumlahKamar', () => this.validateAndCalculate());
                    this.$watch('reset', () => this.resetForm());
                },
    
                // Validasi dan Kalkulasi
                async validateAndCalculate() {
                    this.errorMessage = '';
                    this.isSuccess = false; // Reset status sukses
                    if (!this.validateTanggal()) return;
                    
                    await this.getKetersediaanKamar();
                    this.hitungJumlahHari();
                    this.hitungTotalHarga();
                },
    
                validateTanggal() {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const checkIn = new Date(this.tanggal_check_in);
                    
                    if (checkIn < today && this.reset == false) {
                        // this.errorMessage = "Tanggal Check-In tidak boleh lebih kecil dari hari ini!";
                        alert("Tanggal Check-In tidak boleh lebih kecil dari hari ini!");
                        this.tanggal_check_in = '';
                        this.tanggal_check_out = '';
                        return false;
                    }
    
                    if (!this.tanggal_check_in || !this.tanggal_check_out) return false;
                    
                    const checkOut = new Date(this.tanggal_check_out);
                    if (checkOut <= checkIn && this.reset == false) {
                        alert("Tanggal Check-Out harus lebih besar dari Check-In!");
                        // this.errorMessage = "Tanggal Check-Out harus lebih besar dari Check-In!";
                        this.tanggal_check_out = '';
                        return false;
                    }
    
                    return true;
                },
    
                // Kalkulasi
                hitungJumlahHari() {
                    const diffTime = Math.abs(new Date(this.tanggal_check_out) - new Date(this.tanggal_check_in));
                    this.jumlahHari = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                },
    
                hitungTotalHarga() {
                    this.totalHarga = this.hargaPerMalam * this.jumlahHari * (this.jumlahKamar || 1);
                },
    
                async getKetersediaanKamar() {
                    if (!this.selectedJenisKamar || !this.tanggal_check_in || !this.tanggal_check_out || !this.jumlahKamar) return;
    
                    try {
                        const response = await fetch(
                            `/resepsionis/ketersediaan-kamar/${this.tanggal_check_in}/${this.tanggal_check_out}/${this.selectedJenisKamar}/${this.jumlahKamar}`
                        );
    
                        // console.log('Response Status:', response.status);
                        const responseData = await response.json();
                        this.jumlahTersedia = responseData.kamar_tersedia || 0;
                        this.no_kamar = responseData.no_kamar;
                        // console.log('No kamar:', responseData.no_kamar);
    
                        if (!response.ok) {
                            this.errorMessage = responseData.error || 'Terjadi kesalahan';
                            this.isSuccess = false; // Pastikan status sukses direset
                            return;
                        }
    
                        this.ketersediaan = responseData;
                        this.hargaPerMalam = parseFloat(responseData.harga_final) || 0;
                        this.hitungTotalHarga();
                        this.id_diskon = responseData.id_diskon;
                        this.persentase_diskon = responseData.persentase_diskon;
                        this.id_harga = responseData.id_harga;
                        this.persentase_kenaikan_harga = responseData.persentase_kenaikan_harga;
                        this.isSuccess = true; // Set status sukses
    
                    } catch (error) {
                        console.error('Error:', error);
                        this.errorMessage = 'Terjadi kesalahan saat memeriksa ketersediaan';
                        this.isSuccess = false; // Pastikan status sukses direset
                    }
                },
    
                // Formatting
                formatRupiah(angka) {
                    return new Intl.NumberFormat("id-ID").format(angka || 0);
                },
    
                round(value) {
                    return Math.round(value);
                },
    
                // Reset
                resetForm() {
                    if (!this.reset) return;
                    
                    this.selectedJenisKamar = '';
                    this.selectedTamu = null;
                    this.tanggal_check_out = null;
                    this.tanggal_check_in = null;
                    this.totalHarga = 0;
                    this.jumlahHari = 0;
                    this.ketersediaan = {};
                    this.jumlahKamar = 1;
                    this.errorMessage = '';
                    this.hargaPerMalam = 0;
                    this.isSuccess = false; 

                    // console.log(this.reset);
                    // console.log(this.isSuccess);
                    console.log('Tamu:', this.selectedTamu);
                    console.log('Suc:', this.isSuccess);
                    console.log('res:', this.reset);
                    
                    
                }
            };
        }
    </script>

</div>


