<div wire:poll>
    {{-- @dd($data) --}}


    <div class="overflow-x-auto mt-4">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('tipe_kamar')">
                        <x-sort :$sortDirection :$sortBy :field="'tipe_kamar'" />Tipe Kamar
                    </th>
                    <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('jenis_ranjang')">
                        <x-sort :$sortDirection :$sortBy :field="'jenis_ranjang'" /> Jenis Ranjang
                    </th>
                    <th class="text-sm cursor-pointer text-center">Diskon Yang Berlaku</th>
                    <th  class="text-sm cursor-pointer text-center">Harga Kenaikan Yang Berlaku</th>
                    <th  class="text-sm cursor-pointer text-center">Total Kamar</th>
                    <th class="text-sm cursor-pointer text-center">
                        Action
                    </th>
                </tr>
                
            </thead>

            <tbody>


                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->tipe_kamar }}</td>
                        <td class="text-center">{{ $item->jenis_ranjang }}</td>
                        <td class="text-center">
                            @if ($item->diskon->isNotEmpty())
                                @foreach ($item->diskon as $index => $diskon)
                                    {{ $diskon->kode_diskon }} - {{ round($diskon->persentase) }}%
                                    @if (!$loop->last), @endif
                                @endforeach
                            @else
                                Diskon belum diset
                            @endif
                        </td>
                        
                        <td class="text-center">
                            @if ($item->harga->isNotEmpty())
                                @foreach ($item->harga as $index => $harga)
                                    {{ $harga->kode_harga }} - {{ round($harga->persentase_kenaikan_harga) }}%
                                    @if (!$loop->last), @endif
                                @endforeach
                            @else
                                Harga belum diset
                            @endif
                        </td>
                        
                        <td  class="text-center" >{{ $item->total_kamar }}</td>

                        <td class="text-center">
                            <x-button @click="$dispatch('dispatch-jenis-kamar-table-edit', { id: '{{ $item->id }}' })"
                                type="button" class="text-sm">Edit</x-button>


                                <x-danger-button
                                    @click="$dispatch('dispatch-jenis-kamar-table-delete', { id: '{{ $item->id }}', tipe_kamar: '{{ $item->tipe_kamar }}' })">
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