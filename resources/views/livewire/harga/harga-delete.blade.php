<div>

    <x-dialog-modal wire:model.live="modalHargaDelete" > 
        <x-slot name="title">
            Hapus data harga
        </x-slot>
    
        <x-slot name="content">
            <p>Apakah anda ingin menghapus data harga dengan ID: {{ $id }} dan dengan kode harga : {{ $kode_harga }}</p>

        </x-slot>
    
        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalHargaDelete', false)" wire:loading.attr="disabled">
               Batal
            </x-secondary-button>
    
            <x-danger-button  @click="$wire.del()" class="ms-3" wire:loading.attr="disabled">
                Delete
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>

</div>