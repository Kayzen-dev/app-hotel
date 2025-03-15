<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="kamarData()" x-init="init()">
    
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
                                        <span>Rp <span x-text="formatRupiah(hargaDasar)"></span></span>
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
                                                                                :disabled="selectedTamu != ''"
                                                                            >
                                                                </div>
                                                </div>

                                                {{-- Tamu --}}
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                        <div>
                                                            <label for="no_tlpn"  class="label text-sm font-medium text-gray-600 dark:text-gray-300">Nomor Telepon Tamu</label>
                                                            <input type="number" :disabled="selectedTamu != ''" id="no_tlpn" wire:model="no_tlpn" class="input input-bordered w-full" required>
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
                                                <div class="flex justify-end gap-4 mt-6">
                                                    <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                                                        Simpan
                                                    </x-btn-accent>
                                                </div>

                            </div>
                    </div>
                </div>


        </div>

    </form>

</div>




<script>
    function kamarData() {
        return {
            // State Properties
            selectedJenisKamar: '',
            jumlahKamar: @entangle('jumlahKamar'),
            selectedTamu: '',
            jumlahTersedia: @entangle('jumlahTersedia'),
            tanggal_check_in: '',
            tanggal_check_out: '',
            hargaDasar: @entangle('hargaDasar'),
            hargaNormal: @entangle('hargaNormal'),
            id_diskon: @entangle('id_diskon'),
            persentase_diskon: @entangle('persentase_diskon'),
            id_harga: @entangle('id_harga'),
            persentase_kenaikan_harga: @entangle('persentase_kenaikan_harga'),
            totalHarga: @entangle('totalHarga'),
            jumlahHari: @entangle('jumlahHari'),
            reset: @entangle('reset'),
            jenisKamarList: [],
            tamus: [],
            ketersediaan: {}, // Pastikan ini object, bukan array
    
            round(value) {
                return value ? Math.round(value) : 0; // Rounds to the nearest integer
            },


            // Inisialisasi
            async init() {
                await this.fetchData();
                this.setupWatchers();
                this.updateHarga();
                this.resetForm();
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
                }
            },
    
            // Watchers
            setupWatchers() {
                this.$watch('selectedJenisKamar', () => this.updateHarga());
                this.$watch('tanggal_check_in', () => this.validateAndCalculate());
                this.$watch('tanggal_check_out', () => this.validateAndCalculate());
                this.$watch('jumlahKamar', () => this.updateHarga()); 
                this.$watch('reset', () => this.resetForm());
            },
    
            // Validasi dan Kalkulasi
            async validateAndCalculate() {
                if (!this.validateTanggal()) return;
                
                await this.getKetersediaanKamar();
                this.hitungJumlahHari();
                this.hitungTotalHarga();
            },
    
            validateTanggal() {

                const today = new Date();
                today.setHours(0, 0, 0, 0); // Set waktu ke 00:00:00
                const checkIn = new Date(this.tanggal_check_in);
                 // Validasi tanggal check-in (harus hari ini atau lebih besar)
                 if (checkIn < today) {
                    alert("Tanggal Check-In tidak boleh lebih kecil dari hari ini!");
                    this.tanggal_check_in = '';
                    this.tanggal_check_out = '';
                    return false;
                }

                if (!this.tanggal_check_in || !this.tanggal_check_out) return false;
                
                // Membuat objek Date untuk check-in dan check-out
                const checkOut = new Date(this.tanggal_check_out);
                

                // Validasi tanggal check-out (harus lebih besar dari check-in)
                if (checkOut <= checkIn) {
                    alert("Tanggal Check-Out harus lebih besar dari Check-In!");
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
                this.totalHarga = this.jumlahHari * this.hargaDasar;
            },
    
            // Ketersediaan Kamar
            async getKetersediaanKamar() {
                if (!this.selectedJenisKamar || !this.tanggal_check_in || !this.tanggal_check_out) return;
    
                try {
                    const response = await fetch(
                        `/resepsionis/ketersediaan-kamar/${this.tanggal_check_in}/${this.tanggal_check_out}/${this.selectedJenisKamar}`
                    );
                    
                    const data = await response.json();
                    this.ketersediaan = data;
                    this.id_diskon = data.id_diskon;
                    this.persentase_diskon = data.persentase_diskon;
                    this.id_harga = data.id_harga;
                    this.persentase_kenaikan_harga = data.persentase_kenaikan_harga;
                    this.jumlahTersedia = data?.total_akumulasi_kamar || 0;
                    this.updateHarga();
                } catch (error) {
                    console.error('Error fetching ketersediaan:', error);
                    this.jumlahTersedia = 0;
                }
            },
    
            // Formatting
            formatRupiah(angka) {
                return new Intl.NumberFormat("id-ID").format(angka || 0);
            },
    

    
            // Reset
            resetForm() {
                if (!this.reset) return;
                
                // Simpan nilai hargaDasar sementara
                const savedHargaDasar = this.hargaDasar;
                
                // Reset semua properti kecuali hargaDasar
                this.selectedJenisKamar = '';
                this.tanggal_check_in = '';
                this.tanggal_check_out = '';
                this.totalHarga = 0;
                this.jumlahHari = 0;
                this.ketersediaan = {}; // Hapus data ketersediaan lama
                this.jumlahKamar = 1; // Atur ulang ke default jika perlu
                this.reset = false;
                
                // Kembalikan hargaDasar ke nilai sebelum reset
                this.hargaDasar = savedHargaDasar;
            },
            
            // Update Harga
            updateHarga() {
                // Jika tidak ada jenis kamar yang dipilih, set harga ke 0
                if (!this.selectedJenisKamar) {
                    this.hargaDasar = 0;
                    return;
                }
                
                // Hitung harga berdasarkan ketersediaan atau data lain
                const hargaPerKamar = this.ketersediaan?.harga || 0;
                this.hargaNormal = hargaPerKamar;
                this.hargaDasar = hargaPerKamar * (this.jumlahKamar || 1);
                this.hitungTotalHarga();
            }
        };
    }
</script>
