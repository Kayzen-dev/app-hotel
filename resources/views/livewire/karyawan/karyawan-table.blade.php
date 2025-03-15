<div wire:poll>
    <x-select wire:model.live="paginate" class="text-xs mt-8">
        <option value="3">3</option>
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </x-select>




    <div class="overflow-x-auto">
        <table class="table table-zebra">
          <!-- head -->
          <thead>
            <tr>
                <th>#</th>
                <th class="text-sm" @click="$wire.sortField('nama')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'nama'" /> Nama Karyawan
                </th>
   
                <th class="text-sm" @click="$wire.sortField('posisi')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'posisi'" /> Posisi
                </th>
                <th class="text-sm" @click="$wire.sortField('gaji_pokok')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'gaji_pokok'" /> Gaji Pokok
                </th>
                <th class="text-sm" @click="$wire.sortField('status_kerja')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'status_kerja'" /> Status Kerja
                </th>

                <th class="text-sm" @click="$wire.sortField('shift_kerja')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'shift_kerja'" /> Shift Kerja Karyawan
                </th>
                <th class="text-sm">Action</th>

            </tr>


            <tr>
                <td></td>

                <td>
                    <x-input wire:model.live="form.nama" type="search" placeholder="Cari nama" class="w-full text-sm" />
                </td>
          
                <td>
                    <x-input wire:model.live="form.posisi" type="search" placeholder="Cari Posisi" class="w-full text-sm" />
                </td>
                <td>
                    <x-input wire:model.live="form.gaji_pokok" type="search" placeholder="Cari Gaji Pokok" class="w-full text-sm" />
                </td>
                <td>
                    <x-input wire:model.live="form.status_kerja" type="search" placeholder="Cari Status Kerja" class="w-full text-sm" />
                </td>
             
                <td>
                    <x-input wire:model.live="form.shift_kerja" type="search" placeholder="Cari Shift Kerja" class="w-full text-sm" />
                </td>
                <td>
                    <span wire:loading.class="loading loading-spinner loading-lg text-white"></span>
            </td>


            </tr>

          </thead>

          <tbody>
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                
                <td>{{ $item->nama }}</td>
                <td>{{ $item->posisi }}</td>
                <td>Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                <td>{{ $item->status_kerja }}</td>
                <td>{{ $item->shift_kerja }}</td>
                <td class="text-center">
                    <x-button @click="$dispatch('dispatch-karyawan-table-edit', { id: '{{ $item->id }}' })"
                        type="button" class="text-sm">
                        Detail
                    </x-button>
                        
                    <x-danger-button 
                        @click="$dispatch('dispatch-karyawan-table-delete', { id: '{{ $item->id }}', nama: '{{ $item->nama }}' })">
                        Delete
                    </x-danger-button>
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
</div>