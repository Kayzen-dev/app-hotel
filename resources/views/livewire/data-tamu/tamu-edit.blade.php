<div>
    <x-dialog-modal wire:model.live="modalTamuEdit"> 
        <x-slot name="title">
            Edit Data Tamu
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-2 gap-4">
                <!-- Nama -->
                <div>
                    <x-label for="form.nama" value="Nama Lengkap" />
                    <x-input wire:model="form.nama" id="form.nama" class="input text-gray-300 input-bordered mt-1 w-full" />
                    <x-input-form-error for="form.nama" class="mt-1" />
                </div>

                <!-- Kota -->
                <div>
                    <x-label for="form.kota" value="Kota" />
                    <x-input wire:model="form.kota" id="form.kota" class="input input-bordered  text-gray-300 mt-1 w-full" />
                    <x-input-form-error for="form.kota" class="mt-1" />
                </div>

                <!-- Alamat (Full Width) -->
                <div class="col-span-2">
                    <x-label for="form.alamat" value="Alamat" />
                    <textarea wire:model="form.alamat" id="form.alamat" class="input input-bordered  text-gray-300 mt-1 w-full"></textarea>
                    <x-input-form-error for="form.alamat" class="mt-1" />
                </div>

                <!-- Email -->
                <div>
                    <x-label for="form.email" value="Email" />
                    <x-input type="email" wire:model="form.email" id="form.email" class="input input-bordered  text-gray-300 mt-1 w-full" />
                    <x-input-form-error for="form.email" class="mt-1" />
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <x-label for="form.no_tlpn" value="Nomor Telepon" />
                    <x-input wire:model="form.no_tlpn" id="form.no_tlpn" class="input input-bordered  text-gray-300 mt-1 w-full" />
                    <x-input-form-error for="form.no_tlpn" class="mt-1" />
                </div>

                <!-- Nomor Identitas -->
                <div>
                    <x-label for="form.no_identitas" value="Nomor Identitas" />
                    <x-input wire:model="form.no_identitas" type="number" id="form.no_identitas" class="input input-bordered  text-gray-300 mt-1 w-full" />
                    <x-input-form-error for="form.no_identitas" class="mt-1" />
                </div>

                <!-- Jumlah Anak -->
                <div>
                    <x-label for="form.jumlah_anak" value="Jumlah Anak" />
                    <x-input type="number" wire:model="form.jumlah_anak" id="form.jumlah_anak" class="input input-bordered  text-gray-300 mt-1 w-full" min="0" />
                    <x-input-form-error for="form.jumlah_anak" class="mt-1" />
                </div>

                <!-- Jumlah Dewasa -->
                <div>
                    <x-label for="form.jumlah_dewasa" value="Jumlah Dewasa" />
                    <x-input type="number" wire:model="form.jumlah_dewasa" id="form.jumlah_dewasa" class="input input-bordered  text-gray-300 mt-1 w-full" min="1" />
                    <x-input-form-error for="form.jumlah_dewasa" class="mt-1" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalTamuEdit', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button @click="$wire.edit()" class="ms-3" wire:loading.attr="disabled">
                Ubah
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
