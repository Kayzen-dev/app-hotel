<div wire:poll.3s>
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
                <th class="text-sm">Action</th>
                <th class="text-sm" @click="$wire.sortField('id')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'id'" /> ID User
                </th>
                <th class="text-sm" @click="$wire.sortField('username')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'username'" /> Username
                </th>
                <th class="text-sm" @click="$wire.sortField('name')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'name'" /> Nama User
                </th>
                <th class="text-sm" @click="$wire.sortField('email')" class="cursor-pointer">
                    <x-sort :$sortDirection :$sortBy :field="'email'" /> Email
                </th>
                <th class="cursor-pointer text-sm">
                    Role
                </th>
               
            </tr>
            <tr>
                <td></td>
                <td>
                        <span wire:loading.class="loading loading-spinner loading-lg text-white"></span>
                </td>
                <td>
                    <x-input wire:model.live="form.id" type="search" placeholder="Cari ID User" class="w-full text-sm" />
                </td>
                <td>
                    <x-input wire:model.live="form.username" type="search" placeholder="Cari Username" class="w-full text-sm" />
                </td>
                <td>
                    <x-input wire:model.live="form.name" type="search" placeholder="Cari nama" class="w-full text-sm" />
                </td>
                <td>
                    <x-input wire:model.live="form.email" type="search" placeholder="Cari email" class="w-full text-sm" />
                </td>
                <td>
                </td>
            </tr>
          </thead>

          <tbody>
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <x-button @click="$dispatch('dispatch-admin-crud-user-table-edit', { id: '{{ $item->id }}' })"
                        type="button" class="text-sm">Edit</x-button>
                        
                    <x-danger-button 
                        @click="$dispatch('dispatch-admin-crud-user-table-delete', { id: '{{ $item->id }}', name: '{{ $item->name }}' })">
                        Delete</x-danger-button>
                </td>
                <td class="text-center">{{ $item->id }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->roles->pluck('name')->implode(', ')  }}</td>
                
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