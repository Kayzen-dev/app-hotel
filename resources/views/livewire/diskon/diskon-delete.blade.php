<div>

    <x-dialog-modal wire:model.live="modalDiskonDelete" > 
        <x-slot name="title">
            Hapus data Dsikon
        </x-slot>
    
        <x-slot name="content">
            <p>Apakah anda ingin menghapus data Diskon dengan ID: {{ $id }} dan dengan kode diskon : {{ $kode_diskon }}</p>

        </x-slot>
    
        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalDiskonDelete', false)" wire:loading.attr="disabled">
               Batal
            </x-secondary-button>
    
            <x-danger-button  @click="$wire.del()" class="ms-3" wire:loading.attr="disabled">
                Delete
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>

</div>