<div>

    <x-dialog-modal wire:model.live="modalKamarDelete" > 
        <x-slot name="title">
            Hapus data Kamar
        </x-slot>
    
        <x-slot name="content">
            <p>Apakah anda ingin menghapus data kamar dengan ID: {{ $id }} dan dengan nomor kamar : {{ $no_kamar }}</p>

        </x-slot>
    
        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalKamarDelete', false)" wire:loading.attr="disabled">
               Batal
            </x-secondary-button>
    
            <x-danger-button  @click="$wire.del()" class="ms-3" wire:loading.attr="disabled">
                Delete
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>

</div>