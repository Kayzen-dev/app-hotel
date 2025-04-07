<div wire:poll>
        
    {{-- @dd($data) --}}

    <div class="flex justify-between">
        <h2 class="text-2xl m-5 font-semibold text-gray-700 dark:text-white">Data Tamu</h2>
        <div>
            <x-input wire:model.live="form.nama" type="search" placeholder="Cari Nama Tamu" class="w-full text-sm" />
        </div>
    </div>


    <div class="overflow-x-auto">
        <table class="table table-zebra">
          <!-- head -->
          <thead>
            <tr>
                <th>#</th>
                <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('nama')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'nama'" /> Nama Tamu
                </th>
                <th class="text-sm cursor-pointer text-center" @click="$wire.sortField('no_tlpn')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'no_tlpn'" /> Nomor Telepon
                </th>
                <th class="text-sm cursor-pointer text-center">
                    Jumlah Reservasi
                </th>

            <th class="text-sm cursor-pointer text-center">Action</th>

            </tr>


          </thead>

          <tbody>
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $item->nama ?? 'Data Kosong' }}</td>
                <td class="text-center">{{ $item->no_tlpn ?? 'Data Kosong' }}</td>
                <td  class="text-center" >{{ $item->reservasi_count }}</td>

                <td  class="text-center">

                    <x-button @click="$dispatch('dispatch-tamu-table-edit', { id: '{{ $item->id }}' })"
                        type="button" class="text-sm">
                        @if (in_array('no-data', [$item->kota, $item->alamat, $item->no_identitas, $item->email]))
                            Lengkapi Data Tamu
                            @else
                            Edit
                        @endif
                    </x-button>

                    @if ($item->reservasi_count == 0)
                    <x-button @click="$dispatch('dispatch-tamu-table-delete', { id: '{{ $item->id }}',nama: '{{ $item->nama }}' })"
                        type="button" class="text-sm">
                            Hapus Data Tamu
                    </x-button>
                    @endif

                </td>
            </tr>
            @empty
                <tr>
                    <td>Tidak ada Data</td>
                </tr>
            @endforelse


          </tbody>

        </table>
    </div>



    <div class="mt-3">
        {{ $data->onEachSide(1)->links() }}
    </div> 

    {{-- <br><br><br><br> --}}
</div>