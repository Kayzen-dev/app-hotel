<div>
    <div class="mb-4">
        <h2 class="text-lg font-bold">Laporan Pendapatan Hotel</h2>
    </div>

    <div class="flex space-x-4 mb-4">
        <div>
            <label class="block text-sm font-medium">Tanggal Mulai</label>
            <input type="date" wire:model="tanggalMulai" class="input input-bordered w-full">
        </div>
        <div>
            <label class="block text-sm font-medium">Tanggal Selesai</label>
            <input type="date" wire:model="tanggalSelesai" class="input input-bordered w-full">
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-semibold">Total Pendapatan: 
            <span class="text-green-600">Rp {{ number_format($totalPendapatan, 2, ',', '.') }}</span>
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Reservasi</th>
                    <th>Nama Tamu</th>
                    <th>Kamar</th>
                    <th>Tgl Check-out</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->no_reservasi }}</td>
                        <td>{{ $item->nama_tamu }}</td>
                        <td>{{ $item->kamar }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_check_out)->format('d-M-Y') }}</td>
                        <td>Rp {{ number_format($item->total_harga, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $data->links() }}
    </div>
</div>
