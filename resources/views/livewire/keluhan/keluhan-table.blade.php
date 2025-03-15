<div wire:poll>
    <!-- Tabel untuk Menampilkan Keluhan -->

    
    <div class="overflow-x-auto mt-4">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>No</th>
                    <th class="text-sm cursor-pointer text-center">Nama Tamu</th>
                    <th class="text-sm cursor-pointer text-center">Keluhan</th>
                    <th class="text-sm cursor-pointer text-center">Status Keluhan</th>
                    <th class="text-sm cursor-pointer text-center">Tanggal Keluhan</th>
                    <th class="text-sm cursor-pointer text-center">Action</th>
                </tr>

             
            </thead>

            <tbody>


                @forelse ($data as $item)
                    <tr>
                        
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td  class="text-center">{{ $item->nama_tamu }}</td>
                        <td  class="text-center">{{ $item->keluhan }}</td>
                        <td  class="text-center">{{ $item->status_keluhan }}</td>
                        <td class="text-center">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td class="text-center">
                                @if ($item->status_keluhan == 'diproses')
                                    
                                <button class="btn btn-primary" wire:click="selesai({{ $item->id }})">Selesai</button>
                                @endif
                                <button class="btn btn-neutral" wire:click="hapus({{ $item->id }})">Hapus</button>
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




</div>
