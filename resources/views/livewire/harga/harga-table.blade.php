<div wire:poll>
    {{-- @dd($data) --}}


    <div class="overflow-x-auto mt-4">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>No</th>
                    <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('kode_harga')">
                        <x-sort :$sortDirection :$sortBy :field="'kode_harga'" />Kode Harga
                    </th>
                    <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('persentase_kenaikan_harga')">
                        <x-sort :$sortDirection :$sortBy :field="'persentase_kenaikan_harga'" /> Persentase Kenaikan Harga
                    </th>
                    <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('tanggal_mulai')">
                        <x-sort :$sortDirection :$sortBy :field="'tanggal_mulai'" /> Tanggal Mulai
                    </th>
                    <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('tanggal_berakhir')">
                        <x-sort :$sortDirection :$sortBy :field="'tanggal_berakhir'" /> Tanggal Berakhir
                    </th>
                    <th class="text-sm cursor-pointer text-center">Harga Berlaku di Jenis Kamar</th>

                    <th class="text-sm cursor-pointer text-center">
                        Action
                    </th>
                </tr>

                
             
            </thead>

            <tbody>


                @forelse ($data as $item)
                    <tr>
                        
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td  class="text-center">{{ $item->kode_harga }}</td>
                        <td class="text-center">{{ round($item->persentase_kenaikan_harga) }}%</td>
                        <td  class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-M-y') }}</td>
                        <td  class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-M-y') }}</td>
                        <td  class="text-center">{{  $item->jenisKamar->tipe_kamar . ' - ' . $item->jenisKamar->jenis_ranjang }}</td>

                        <td class="text-center">
                            <x-button @click="$dispatch('dispatch-harga-table-edit', { id: '{{ $item->id }}' })"
                                type="button" class="text-sm">Detail</x-button>


                                <x-danger-button
                                    @click="$dispatch('dispatch-harga-table-delete', { id: '{{ $item->id }}', kode_harga: '{{ $item->kode_harga }}' })">
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