<div wire:poll>
    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-sm text-center">Nomor Reservasi</th>
                    <th class="text-sm text-center">Nama Tamu</th>
                    <th class="text-sm text-center">Kamar</th>
                    <th class="text-sm text-center">Tanggal Check In</th>
                    <th class="text-sm text-center">Status</th>
                    <th class="text-sm text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->no_reservasi }}</td>
                        <td class="text-center">{{ $item->nama_tamu }}</td>
                        <td class="text-center">{{ $item->kamar }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_check_in)->format('d-M-y') }}</td>
                        <td class="text-center">{{ $item->status_reservasi }}</td>
                        <td class="text-center">
                            <button wire:click="showDetail({{ $item->id_riwayat }})" class="btn btn-primary btn-sm">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $data->links() }}
    </div>

    <!-- Modal Dialog -->
    <x-dialog wire:model="isOpen">
        <x-slot name="title">
            Detail Reservasi - {{ $selectedRiwayat->no_reservasi ?? '' }}
        </x-slot>

        <x-slot name="content">
            @if($selectedRiwayat)
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>Nomor Reservasi:</strong> {{ $selectedRiwayat->no_reservasi }}</div>
                    <div><strong>Nama Tamu:</strong> {{ $selectedRiwayat->nama_tamu }}</div>
                    <div><strong>Kamar:</strong> {{ $selectedRiwayat->kamar }}</div>
                    <div><strong>Tanggal Check-in:</strong> {{ \Carbon\Carbon::parse($selectedRiwayat->tanggal_check_in)->format('d-M-y') }}</div>
                    <div><strong>Tanggal Check-out:</strong> {{ \Carbon\Carbon::parse($selectedRiwayat->tanggal_check_out)->format('d-M-y') }}</div>
                    <div><strong>Jumlah Hari:</strong> {{ $selectedRiwayat->jumlah_hari }}</div>
                    <div><strong>Jumlah Pembayaran:</strong> Rp {{ number_format($selectedRiwayat->jumlah_pembayaran, 2, ',', '.') }}</div>
                    <div><strong>Total Harga:</strong> Rp {{ number_format($selectedRiwayat->total_harga, 2, ',', '.') }}</div>
                    <div><strong>Kembalian:</strong> Rp {{ number_format($selectedRiwayat->kembalian, 2, ',', '.') }}</div>
                    <div><strong>Resepsionis:</strong> {{ $selectedRiwayat->resepsionis ?? '-' }}</div>
                    <div><strong>Denda:</strong> Rp {{ number_format($selectedRiwayat->denda, 2, ',', '.') }}</div>
                    <div><strong>Status:</strong> {{ $selectedRiwayat->status_reservasi }}</div>
                    <div><strong>Keterangan:</strong> {{ $selectedRiwayat->keterangan ?? '-' }}</div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button wire:click="closeDetail" class="btn btn-secondary">Tutup</button>
        </x-slot>
    </x-dialog>
</div>
