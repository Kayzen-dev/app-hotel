<div>
    <x-button @click="$wire.set('modalKaryawanCreate', true)">
        Tambah Karyawan Baru
    </x-button>

    <x-dialog wire:model.live="modalKaryawanCreate" submit="save">
        <x-slot name="title">
            Tambah Karyawan
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-3 gap-5">
                <!-- Nama -->
                <div>
                    <label for="form.nama"  class="label" > Nama Lengkap </label>
                    <input wire:model="form.nama" class="input input-bordered w-full" required />
                    <x-input-form-error for="form.nama" />
                </div>

                <!-- No Telepon -->
                <div>
                    <label for="form.no_tlpn" class="label" >Nomor Telepon </label>
                    <input wire:model="form.no_tlpn" type="tel" class="input input-bordered w-full" required />
                    <x-input-form-error for="form.no_tlpn" />
                </div>

               

                <!-- Shift Kerja -->
                <div>
                    <label for="form.shift_kerja" class="label" > Shift Kerja</label>
                    <select wire:model="form.shift_kerja" class="select select-bordered w-full">
                        <option value="" >Pilih Shift</option>
                        <option value="pagi">Pagi</option>
                        <option value="siang">Siang</option>
                        <option value="malam">Malam</option>
                    </select>
                    <x-input-form-error for="form.shift_kerja" />
                </div>


            </div>

            <div class="grid grid-cols-3 gap-2">
                    
                <!-- Gaji Pokok -->
                <div>
                    <label for="form.gaji_pokok" class="label" > Gaji Pokok </label>
                    <input wire:model="form.gaji_pokokRp" id="gaji_pokok" type="text" class="input input-bordered w-full" required />
                    <x-input-form-error for="form.gaji_pokokRp" />
                </div>

                 <!-- Jenis Kelamin -->
                 <div>
                    <label for="form.jenis_kelamin"  class="label" > Jenis Kelamin </label>
                    <select wire:model="form.jenis_kelamin" class="select select-bordered w-full">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <x-input-form-error for="form.jenis_kelamin" />
                </div>

                  <!-- Tanggal Bergabung -->
                  <div>
                    <label for="form.tanggal_bergabung"  class="label" > Tanggal Bergabung</label>
                    <input wire:model="form.tanggal_bergabung" type="date" class="input input-bordered w-full" />
                    <x-input-form-error for="form.tanggal_bergabung" />
                </div>

            </div>

            <div class="grid grid-cols-2 gap-2">
                
                  <!-- Posisi -->
                  <div>
                    <label for="form.posisi"  class="label" > Posisi Jabatan </label>
                    <select wire:model="form.posisi" class="select select-bordered w-full">
                        <option value="">Pilih Posisi</option>
                        @foreach([
                            'General Manager', 'Asisten Manajer', 'HRD', 'Akuntan',
                            'Resepsionis', 'Bellboy', 'Concierge', 'Room Attendant',
                            'Housekeeper', 'Chef', 'Waiter', 'Bartender', 'Satpam',
                            'Teknisi', 'IT Support'
                        ] as $posisi)
                            <option value="{{ $posisi }}">{{ $posisi }}</option>
                        @endforeach
                    </select>
                    <x-input-form-error for="form.posisi" />
                </div>
                <!-- Alamat -->
                <div>
                    <label for="form.alamat"  class="label" > Alamat Lengkap </label>
                    <textarea wire:model="form.alamat" class="textarea textarea-bordered w-full" rows="3"></textarea>
                    <x-input-form-error for="form.alamat" />
                </div>

              

            </div>

        </x-slot>

        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalKaryawanCreate', false)">
                Batal
            </x-secondary-button>
            <x-button class="ms-3">Simpan</x-button>
        </x-slot>
    </x-dialog>
</div>

<script>
    document.getElementById("gaji_pokok").addEventListener("input", function () {
    let angka = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
    let formatRupiah = new Intl.NumberFormat("id-ID").format(angka); // Format ke Rupiah
    this.value = formatRupiah ? "Rp " + formatRupiah : "";
});

</script>