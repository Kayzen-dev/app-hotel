<div  x-data="harga()" x-init="init()">

    <x-dialog-modal wire:model.live="modalHargaEdit" :id="'modal-harga-edit'" submit="edit">
        <x-slot name="title">
            Edit Harga
        </x-slot>
  
        <x-slot name="content">
            <div class="grid grid-cols-2 gap-10">

                <div>
                    <label for="kode_harga" class="label">Kode Harga</label>
                    <input wire:model="form.kode_harga" type="text" id="kode_harga" readonly required class="input input-bordered text-gray-300 w-full">
                    <x-input-form-error for="form.kode_harga" class="mt-1" />
                </div>
    
                <div>
                    <label for="persentase_kenaikan_harga" class="label">Persentase Kenaikan Harga</label>
                    <div class="relative z-0 flex max-w-48 space-x-px">
                        <input id="persentase_kenaikan_harga" wire:model="form.persentase_kenaikan_harga" type="number" class="block w-full cursor-pointer text-gray-300 appearance-none rounded-l-md  bg-base-100 px-3 text-sm transition focus:z-10 focus:border-neutral-100 focus:outline-none focus:ring-2 focus:ring-neutral-100">
                        <button type="button" class="inline-flex w-auto cursor-pointer select-none appearance-none items-center justify-center space-x-1 rounded-r  bg-base-100 px-3 py-2 text-lg font-medium text-white">%</button>
                    </div>
                    <x-input-form-error for="form.persentase_kenaikan_harga" class="mt-1" />
                </div>


       
    
            </div>


                            <!-- Row 2 -->
                            <div class="grid grid-cols-2 gap-4">

                                
                                <div>
                                    <label for="tanggal_mulai" class="label">Tanggal Mulai Diskon</label>
                                    <input type="date" wire:model="form.tanggal_mulai" x-model="tanggalMulai" @change="validateTanggal" id="tanggal_mulai" required class="input input-bordered text-gray-300 w-full">
                                    <x-input-form-error for="form.tanggal_mulai" class="mt-1" />
                                </div>
                                <div>
                                    <label for="tanggal_berakhir" class="label">Tanggal Berakhir Diskon</label>
                                    <input type="date" wire:model="form.tanggal_berakhir" x-model="tanggalBerakhir" @change="validateTanggal" id="tanggal_berakhir" required class="input input-bordered text-gray-300 w-full">
                                    <x-input-form-error for="form.tanggal_berakhir" class="mt-1" />
                                </div>
                                
                                <div>
                                    <label for="id_jenis_kamar" class="label">Jenis Kamar</label>
                                    <select id="id_jenis_kamar" wire:model="form.id_jenis_kamar" x-model="selectedJenisKamar"  class="select text-gray-300 select-bordered w-full max-w-xs border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                                        <option value="">-- Pilih Jenis Kamar --</option>
                                        <template x-for="jenis in jenisKamarList" :key="jenis.id">
                                            <option :value="jenis.id" x-text="`${jenis.tipe_kamar} - ${jenis.jenis_ranjang}`"></option>
                                        </template>
                                    </select>
                                    <x-input-form-error for="form.id_jenis_kamar" class="mt-1" />
                                </div>
            
                            </div>
  
  
  
        </x-slot>
  
        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalHargaEdit', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>
  
            <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                PerBaharui
            </x-btn-accent>
        </x-slot>
  
  
    </x-dialog-modal>
  
  
  
  </div>
  

  <script>
    document.getElementById("kode_harga").addEventListener("input", function () {
this.value = this.value.toUpperCase().replace(/\s/g, ""); 
});

</script>



<script>

function harga() {
    return {
        tanggalMulai:  @entangle('form.tanggal_mulai'), // State untuk tanggal mulai
        tanggalBerakhir:  @entangle('form.tanggal_berakhir'), // State untuk tanggal berakhir
        selectedJenisKamar: '', // Variable untuk memilih jenis kamar
        jenisKamarList: [], // Daftar jenis kamar yang diambil dari server

        validateTanggal() {
                // Pastikan kedua tanggal ada
                if (this.tanggalMulai && this.tanggalBerakhir) {
                    const checkIn = new Date(this.tanggalMulai);
                    const checkOut = new Date(this.tanggalBerakhir);

                    // Validasi jika tanggal berakhir tidak lebih besar dari tanggal mulai
                    if (checkOut <= checkIn) {
                        alert("Tanggal Berakhir harga harus lebih besar dari Tanggal Mulai!");
                        this.tanggalBerakhir = ''; // Reset tanggal berakhir jika validasi gagal
                    }
                }
            },
            // Mengambil daftar jenis kamar dari API/Server
            fetchJenisKamarList() {
                fetch(`/resepsionis/jenis-kamar/harga/${this.tanggalMulai}/${this.tanggalBerakhir}`) // Ganti dengan endpoint API yang sesuai
                    .then(response => response.json())
                    .then(data => {
                        this.jenisKamarList = data;
                    })
                    .catch(error => {
                        console.error('Gagal mengambil jenis kamar:', error);
                    });
            },

            init() {
                this.fetchJenisKamarList();
                setInterval(() => {
                        this.fetchJenisKamarList();
                    }, 3000);
            }

        };
}
</script>

