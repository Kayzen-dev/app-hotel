<div wire:poll>
    
    <div x-data="ketersediaan()" class="dropdown" x-init="init()">
        <div tabindex="0" role="button" class="btn m-3">Ketersediaan Kamar</div>
        <div tabindex="0" class="dropdown-content card card-compact bg-neutral text-gray-300 z-[1] w-[600px] p-2 shadow">
            <div class="card-body p-3">
                <div class="flex space-x-5 mb-1">
                    <div>
                        <label class="block text-sm p-2 font-medium">Tanggal Check In</label>
                        <input type="date" x-model="tanggal_check_in" @change="validateTanggal(); getKetersediaanKamar()" class="input input-bordered input-md w-full">
                    </div>
                    <div>
                        <label class="block text-sm p-2 font-medium">Tanggal Check Out</label>
                        <input type="date" x-model="tanggal_check_out" @change="validateTanggal(); getKetersediaanKamar()" class="input input-bordered input-md w-full">
                    </div>
    
                    <div>
                        <label for="id_jenis_kamar" class="label text-white p-2 font-medium">Pilih Jenis Kamar</label>
                        <select id="id_jenis_kamar" x-model="selectedJenisKamar" @change="getKetersediaanKamar()" 
                            class="select text-gray-300 select-bordered w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">-- Pilih Jenis Kamar --</option>
                        <template x-for="jenis in jenisKamarList" :key="jenis.id">
                            <option :value="jenis.id" x-text="`${jenis.tipe_kamar} - ${jenis.jenis_ranjang}`"></option>
                        </template>
                    </select>

                    </div>
                </div>
    
                <div class="flex flex-col items-center justify-center h-auto p-2">
                    <!-- Tabel -->
                    <div class="overflow-x-auto w-full">
                        <div class="h-[100px] overflow-y-auto"> 
                            <table class="table-auto w-full text-sm rounded-lg">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th class="px-2 py-1 text-sm text-center">Tanggal</th>
                                        <th class="px-2 py-1 text-sm text-center">Jenis Kamar</th>
                                        <th class="px-2 py-1 text-sm text-center">Total Kamar</th>
                                        <th class="px-2 py-1 text-sm text-center">Total Tersedia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in ketersediaan.ketersediaan_per_hari" :key="item.tanggal_ID">
                                        <tr>
                                            <td class="px-2 py-1 text-sm" x-text="item.tanggal_ID"></td>
                                            <td class="px-2 py-1 text-sm" x-text="item.jenis_kamar"></td>
                                            <td class="px-2 py-1 text-sm" x-text="item.total_kamar"></td>
                                            <td class="px-2 py-1 text-sm" x-text="item.kamar_tersedia"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
    
                    <!-- Total Tersedia -->
                    <div class="flex justify-end w-full mt-4">
                        <table class="w-1/2 text-right">
                            <tr>
                                <th class="px-2 py-1 text-gray-300 text-sm">Total Kamar Tersedia:</th>
                                <td class="px-2 py-1 text-gray-300 font-bold text-lg" x-text="ketersediaan.total_akumulasi_kamar"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
























    <div class="overflow-x-auto mt-4">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-sm cursor-pointer text-center">
                        Nomor Kamar
                    </th>
                    <th class="text-sm cursor-pointer text-center">
                        Status Kamar
                    </th>
                    <th class="text-sm cursor-pointer text-center">
                        Tipe Kamar
                    </th>
                    <th class="text-sm cursor-pointer text-center">
                        Jenis Ranjang
                    </th>
                    <th class="text-sm cursor-pointer text-center">
                        Harga Kamar
                    </th>
                    {{-- <th class="text-sm cursor-pointer text-center">
                        Total data reservasi kamar ini
                    </th> --}}
                    <th class="text-sm cursor-pointer text-center">
                        Action
                    </th>
                </tr>
                
            </thead>

            <tbody>


                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td  class="text-center">{{ $item->no_kamar }}</td>
                        <td  class="text-center">{{ $item->status_kamar }}</td>
                        <td  class="text-center">{{ $item->jenisKamar->tipe_kamar }}</td>
                        <td  class="text-center">{{ $item->jenisKamar->jenis_ranjang }}</td>
                        <td class="text-center">Rp {{ number_format($item->harga_kamar, 0, ',', '.') }}</td>
                        {{-- <td  class="text-center" >{{ $item->reservasi_count }}</td> --}}
                    
                        <td class="text-center">
                            <x-button @click="$dispatch('dispatch-kamar-table-edit', { id: '{{ $item->id }}' })"
                                type="button" class="text-sm">Edit Kamar</x-button>


                                <x-danger-button
                                    @click="$dispatch('dispatch-kamar-table-delete', { id: '{{ $item->id }}', no_kamar: '{{ $item->no_kamar }}' })">
                                    Delete
                                </x-danger-button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>




<script>
    function ketersediaan() {
        return {
            // State Properties
            selectedJenisKamar: '',
            tanggal_check_in: '',
            tanggal_check_out: '',
            jenisKamarList: [], 
            ketersediaan: {
                ketersediaan_per_hari: [],
                total_akumulasi_kamar: 0
            },

            validateTanggal() {
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Set waktu ke 00:00:00
                const checkIn = new Date(this.tanggal_check_in);
                // Validasi tanggal check-in (harus hari ini atau lebih besar)
                if (checkIn < today) {
                    alert("Tanggal Check-In tidak boleh lebih kecil dari hari ini!");
                    this.tanggal_check_in = '';
                    this.tanggal_check_out = '';
                    return false;
                }

                if (!this.tanggal_check_in || !this.tanggal_check_out) return false;

                // Membuat objek Date untuk check-in dan check-out
                const checkOut = new Date(this.tanggal_check_out);


                // Validasi tanggal check-out (harus lebih besar dari check-in)
                if (checkOut <= checkIn) {
                    alert("Tanggal Check-Out harus lebih besar dari Check-In!");
                    this.tanggal_check_out = '';
                    return false;
                }

                 return true;
            },

                            // Method to fetch the availability data
                async getKetersediaanKamar() {
                    if (!this.selectedJenisKamar || !this.tanggal_check_in || !this.tanggal_check_out) return;

                    try {
                        const response = await fetch(`/resepsionis/ketersediaan/${this.tanggal_check_in}/${this.tanggal_check_out}/${this.selectedJenisKamar}`);

                        // if (!response.ok) {
                        //     throw new Error(HTTP error! Status: ${response.status});
                        // }

                        const data = await response.json();
                        this.ketersediaan = data;
                    } catch (error) {
                        console.error('Error fetching ketersediaan:', error);
                        this.ketersediaan = { ketersediaan_per_hari: [], total_akumulasi_kamar: 0 };
                    }
                },


            async fetchJenisKamar() {
                try {
                    const response = await fetch('/resepsionis/jenis-kamar');
                    this.jenisKamarList = await response.json();
                } catch (error) {
                    console.error('Error:', error);
                }
            },


            init() {
                this.fetchJenisKamar();

                setInterval(() => {
                   this.fetchJenisKamar();
                }, 1000);

            }

        };
    }
</script>