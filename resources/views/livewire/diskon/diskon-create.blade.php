<div x-data="diskon()" x-init="init()">

    <x-secondary-button @click="$wire.set('modalDiskonCreate', true)">
        Tambah Diskon
    </x-secondary-button>

    <x-dialog-modal wire:model.live="modalDiskonCreate" :id="'modal-diskon-create'" submit="save">
        <x-slot name="title">
        Tambah Diskon
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-2 gap-10">
                <div>
                    <label for="kode_diskon" class="label">Kode Diskon</label>
                    <input wire:model="form.kode_diskon" type="text" id="kode_diskon" required class="input input-bordered text-gray-300 w-full">
                    <x-input-form-error for="form.kode_diskon" class="mt-1" />
                </div>

                <div>
                    <label for="persentase" class="label">Persentase Diskon</label>
                    <div class="relative z-0 flex max-w-48 space-x-px">
                        <input id="persentase" wire:model="form.persentase" type="number" class="block w-full cursor-pointer text-gray-300 appearance-none rounded-l-md  bg-base-100 px-3 text-sm transition focus:z-10 focus:border-neutral-100 focus:outline-none focus:ring-2 focus:ring-neutral-100">
                        <button type="button" class="inline-flex w-auto cursor-pointer select-none appearance-none items-center justify-center space-x-1 rounded-r  bg-base-100 px-3 py-2 text-lg font-medium text-white">%</button>
                    </div>
                    <x-input-form-error for="form.persentase" class="mt-1" />
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
                    <select id="id_jenis_kamar" wire:model="form.id_jenis_kamar" x-model="selectedJenisKamar" class="select select-bordered text-gray-300 w-full max-w-xs border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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
            <x-secondary-button @click="$wire.set('modalDiskonCreate', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                Simpan
            </x-btn-accent>
        </x-slot>

    </x-dialog-modal>
</div>

