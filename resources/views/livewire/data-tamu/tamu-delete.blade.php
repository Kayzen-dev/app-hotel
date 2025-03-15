<div>

    <x-dialog-modal wire:model.live="modalTamuDelete" > 
        <x-slot name="title">
            Hapus data Tamu
        </x-slot>
    
        <x-slot name="content">
            <p>Apakah anda ingin menghapus data tamu dengan ID: {{ $id }} dan dengan nama : {{ $nama }}</p>
            <p>Ini akan menghapus data reservasi yang dimiliki</p>

        </x-slot>
    
        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalTamuDelete', false)" wire:loading.attr="disabled">
               Batal
            </x-secondary-button>
    
            <x-danger-button  @click="$wire.del()" class="ms-3" wire:loading.attr="disabled">
                Delete
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>

</div>