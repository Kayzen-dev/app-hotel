<div>
    <x-dialog wire:model.live="modalKaryawanEdit" submit="edit">
        <x-slot name="title">
            Edit Karyawan
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
                    
                <div x-data="{ gajiPokok: ''}">
                    <label for="gaji_pokok" class="label">Gaji Pokok Baru</label>
                    <input 
                        wire:model="form.gaji_pokokRp"
                        x-model="gajiPokok"
                        type="text" 
                        id="gaji_pokok" 
                        placeholder="Masukan Gaji Pokok Baru"
                        class="input input-bordered w-full" 
                        @input="gajiPokok = formatRupiah($event.target.value)" />
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
            <x-secondary-button @click="$wire.set('modalKaryawanEdit', false)" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3" wire:loading.attr="disabled">
                Update
            </x-button>
        </x-slot>
    </x-dialog>

</div>

<script>
    function formatRupiah(value) {
        // Menghapus karakter non-numerik
        let angka = value.replace(/\D/g, "");

        // Format angka menjadi format Rupiah
        let formatRupiah = new Intl.NumberFormat("id-ID").format(angka);

        // Menambahkan "Rp" di depan jika ada angka
        return formatRupiah ? "Rp " + formatRupiah : "";
    }   
</script>
