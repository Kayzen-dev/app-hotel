<div>

    <x-dialog wire:model.live="modalJenisKamarEdit" :id="'modal-jenis-kamar-edit'" submit="edit">
        <x-slot name="title">
            Edit JenisK Kamar
        </x-slot>
  
        <x-slot name="content">
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label for="tipe_kamar" class="label">Tipe Kamar</label>
                    <input wire:model="form.tipe_kamar" type="text" id="tipe" required class="input input-bordered w-full">
                    <x-input-form-error for="form.tipe_kamar" class="mt-1" />
                </div>
    
                <div>
                    <label for="jenis_ranjang" class="label">Jenis Ranjang</label>
                    <input wire:model="form.jenis_ranjang" type="text" id="jenis" required class="input input-bordered w-full">
                    <x-input-form-error for="form.jenis_ranjang" class="mt-1" />
                </div>

                <div x-data="{ hargaRp: '' }">
                    <label for="harga_normal" class="label">Harga Kamar baru</label>
                    <input 
                        wire:model="form.hargaRp"
                        x-model="hargaRp"
                        type="text" 
                        id="harga_normal" 
                        placeholder="Masukan Harga Kamar Baru"
                        class="input input-bordered w-full" 
                        @input="hargaRp = formatRupiah($event.target.value)" />
                </div>
    
            </div>
  
  
        </x-slot>
  
        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalJenisKamarEdit', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>
  
            <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
                PerBaharui
            </x-btn-accent>
        </x-slot>
  
  
    </x-dialog>
  
  
  
  </div>


<script>
        document.getElementById("tipe").addEventListener("input", function () {
    this.value = this.value.toUpperCase(); 
    });

    document.getElementById("jenis").addEventListener("input", function () {
    this.value = this.value.toUpperCase(); 
    });

        document.getElementById("harga_normal").addEventListener("input", function () {
            let angka = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
            let formatRupiah = new Intl.NumberFormat("id-ID").format(angka); // Format ke Rupiah
            this.value = formatRupiah ? "Rp " + formatRupiah : "";
        });
</script>
  