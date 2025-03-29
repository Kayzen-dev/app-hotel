<div>
    <x-dialog wire:model.live="modalKamarEdit" :id="'modal-kamar-edit'" submit="edit">
        <x-slot name="title">
            Edit Kamar
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-2 gap-10">
                <div>
                    <label for="no_kamar" class="label">Nomor Kamar</label>
                    <input wire:model="form.no_kamar" type="text" id="no_kamar" readonly required class="input input-bordered w-full">
                    <x-input-form-error for="form.no_kamar" class="mt-1" />
                </div>


                <div>
                    <label for="status_kamar" class="label">Status Kamar</label>
                    <select id="status_kamar"  wire:model="form.status_kamar"  required class="select select-bordered w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="tersedia" selected>Tersedia</option>
                        <option value="perbaikan">Perbaikan</option>
                    </select>
                    <x-input-form-error for="form.status_kamar" class="mt-1" />
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalKamarEdit', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                Simpan
            </x-btn-accent>
        </x-slot>
    </x-dialog>
</div>
