<div x-data="kamarRealtime()" x-init="init()">
    <x-secondary-button @click="$wire.set('modalKamarCreate', true)">
        Tambah Kamar
    </x-secondary-button>

    <x-dialog-modal wire:model.live="modalKamarCreate" :id="'modal-kamar-create'" submit="save">
        <x-slot name="title">
            Tambah Kamar
        </x-slot>

        <x-slot name="content">
            <div>
                <label for="id_jenis_kamar" class="label text-white text-lg">Jenis Kamar</label>
                <select id="id_jenis_kamar" wire:model="form.id_jenis_kamar" x-model="selectedJenisKamar" @change="fetchHarga" class="select text-gray-300 select-bordered w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">-- Pilih Jenis Kamar --</option>
                    <template x-for="jenis in jenisKamarList" :key="jenis.id">
                        <option :value="jenis.id" x-text="`${jenis.tipe_kamar} - ${jenis.jenis_ranjang}`"></option>
                    </template>
                </select>
                <x-input-form-error for="form.id_jenis_kamar" class="mt-1" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalKamarCreate', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                Simpan
            </x-btn-accent>
        </x-slot>


    </x-dialog-modal>
</div>

<script>

    function kamarRealtime() {
        return {
            selectedJenisKamar: '', // Variable untuk memilih jenis kamar
            jenisKamarList: [], // Daftar jenis kamar yang diambil dari server

            // Mengambil daftar jenis kamar dari API/Server
            fetchJenisKamarList() {
                fetch('/resepsionis/jenis-kamar') // Ganti dengan endpoint API yang sesuai
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
