<div>

    <x-secondary-button @click="$wire.set('modalJenisKamarCreate', true)">
        Tambah Jenis Kamar
      </x-secondary-button>


  <x-dialog wire:model.live="modalJenisKamarCreate" :id="'modal-jenis-kamar-create'" submit="save">
      <x-slot name="title">
          Tambah Jenis Kamar
      </x-slot>

      <x-slot name="content">
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label for="tipe_kamar" class="label">Tipe Kamar</label>
                        <input wire:model="form.tipe_kamar" type="text" id="tipe_kamar" required class="input input-bordered w-full">
                        <x-input-form-error for="form.tipe_kamar" class="mt-1" />
                    </div>
        
                    <div>
                        <label for="jenis_ranjang" class="label">Jenis Ranjang</label>
                        <input wire:model="form.jenis_ranjang" type="text" id="jenis_ranjang" required class="input input-bordered w-full">
                        <x-input-form-error for="form.jenis_ranjang" class="mt-1" />
                    </div>

        
                </div>


                <div class="grid grid-cols-3 gap-4">
                    <div>
                            <label for="no_kamar" class="label">Nomor Awal Kamar</label>
                            <input wire:model="form.no_kamar" type="text" id="nomorKamar" required class="input input-bordered w-full">
                            <x-input-form-error for="form.no_kamar" class="mt-1" />
    
                        
                    </div>

                    <div>
                            <label for="harga_normal" class="label">Harga Kamar</label>
                            <input wire:model="form.hargaRp" type="text" id="hargaKamar" required class="input input-bordered w-full">
                            <x-input-form-error for="form.hargaRp" class="mt-1" />
                    </div>
                    
                    <div>
                            <label for="jumlahKamar" class="label">Jumlah Kamar</label>
                            <input wire:model="form.jumlahKamar" type="number" id="jumlahKamar" required class="input input-bordered w-full">
                            <x-input-form-error for="form.jumlahKamar" class="mt-1" />
                    </div>

                </div>

      </x-slot>

      <x-slot name="footer">
          <x-secondary-button @click="$wire.set('modalJenisKamarCreate', false)" wire:loading.attr="disabled">
              Batal
          </x-secondary-button>

          <x-btn-accent type="submit" class="ms-3 btn-accent" wire:loading.attr="disabled">
              Simpan
          </x-btn-accent>
      </x-slot>


  </x-dialog>



</div>



<script>
    document.getElementById("nomorKamar").addEventListener("input", function () {
        this.value = this.value.toUpperCase().replace(/\s/g, ""); 
    });

    document.getElementById("hargaKamar").addEventListener("input", function () {
    let angka = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
    let formatRupiah = new Intl.NumberFormat("id-ID").format(angka); // Format ke Rupiah
    this.value = formatRupiah ? "Rp " + formatRupiah : "";
    });

    document.getElementById("tipe_kamar").addEventListener("input", function () {
    this.value = this.value.toUpperCase(); 
    });

    document.getElementById("jenis_ranjang").addEventListener("input", function () {
    this.value = this.value.toUpperCase(); 
    });
</script>