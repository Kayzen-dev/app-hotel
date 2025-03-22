<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoiceData['namaTamu'] }} - {{ $invoiceData['invoiceNumber'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };

        window.onafterprint = function() {
            console.log("Pengguna selesai mencetak atau membatalkan");
        };

        window.onbeforeprint = function() {
            console.log("Sedang mencetak...");
        };

        window.addEventListener("focus", function() {
        });

        setTimeout(() => {
            window.location.href = "{{ route('resepsionis.reservasi.data') }}";
        }, 3000);

    </script>
</head>
<body class="bg-gray-100 p-4 min-h-screen flex justify-center items-start">
    <div class="max-w-3xl bg-white p-6 rounded-lg">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <img class="h-20 w-20 mr-2" src="{{ asset('images/logo_hotel.png') }}" alt="Logo" />
                <div class="text-gray-700 font-semibold text-lg">Hotel Crown Pangandaran Syariah</div>
            </div>
            <div class="text-gray-700 text-right">
                <div class="font-bold text-xl mb-2">INVOICE</div>
                <div class="text-sm">Tanggal: {{ $invoiceData['invoiceDate'] }}</div>
                <div class="text-sm">Invoice #: {{ $invoiceData['invoiceNumber'] }}</div>
            </div>
        </div>
    
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <h3 class="font-bold text-gray-700">Hotel Crown Pangandaran Syariah</h3>
                <p class="text-gray-600 text-sm">
                    Jl. Pangandaran No. 10, Jawa Barat<br>
                    Email: crownhotelpangandaran1@gmail.com<br>
                    Telp: (0858) 05362620
                </p>
            </div>
            <div>
                <h3 class="font-bold text-gray-700">Kepada:</h3>
                <p class="text-gray-600 text-sm">
                    {{ $invoiceData['namaTamu'] }}<br>
                    {{ $invoiceData['alamatTamu'] }}, {{ $invoiceData['kotaTamu'] }}<br>
                    {{ $invoiceData['emailTamu'] }}<br>
                    No. Telepon: {{ $invoiceData['teleponTamu'] }}
                </p>
            </div>
        </div>
    
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-gray-700">Kamar</th>
                    <th class="p-2 text-gray-700">No. Kamar</th>
                    <th class="p-2 text-gray-700">Durasi</th>
                    <th class="p-2 text-gray-700">Harga/Malam</th>
                    <th class="p-2 text-gray-700">Total</th>
                    <th class="p-2 text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceData['invoice'] as $item)
                <tr class="text-center">
                    <td class="p-2 text-gray-700">{{ $item['jenisKamar'] }}</td>
                    <td class="p-2 text-gray-700">{{ $item['no_kamar'] }}</td>
                    <td class="p-2 text-gray-700">{{ $item['jumlah_malam'] }} Malam</td>
                    <td class="p-2 text-gray-700">Rp {{ number_format($item['harga_akhir'], 0, ',', '.') }}</td>
                    <td class="p-2 text-gray-700">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    <td class="p-2 text-gray-700">{{ $invoiceData['status_reservasi'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="flex justify-between mt-6 gap-8">
            <!-- Bagian Kiri: Informasi Kamar & Pembayaran -->
            <div class="flex-1">
                <table class="w-full">
                    @if ($invoiceData['status_reservasi'] == "Check in")
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal w-1/2">Total Kamar:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">{{ $invoiceData['jumlahKamar']}}</td>
                        </tr>
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal">Jumlah Pembayaran:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($invoiceData['jumlahPembayaran'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal">Kembalian:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($invoiceData['kembalian'], 0, ',', '.') }}</td>
                        </tr>
                    @elseif ($invoiceData['status_reservasi'] == "Check out")
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal w-1/2">Total Kamar:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">{{ $invoiceData['jumlahKamar']}}</td>
                        </tr>
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal">Jumlah Pembayaran:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($invoiceData['jumlahPembayaran'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal">Kembalian:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($invoiceData['kembalian'], 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <th class="p-2 text-gray-600 text-sm font-normal w-1/2">Total Kamar:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">{{ $invoiceData['jumlahKamar']}}</td>
                        </tr>
                        
                    @endif
                </table>
            </div>
        
            <!-- Garis Pemisah Vertikal -->
            <div class="border-r border-gray-200 h-auto my-2"></div>
        
            <!-- Bagian Kanan: Total & Denda -->
            <div class="flex-1">
                <table class="w-full">
                    @if ($invoiceData['status_reservasi'] == "Check in")
                        <tr class="border-t border-gray-200">
                            <th class="p-2 text-gray-900 font-bold pt-3">Total:</th>
                            <td class="p-2 text-red-600 font-bold text-right pt-3">Rp {{ number_format($invoiceData['total_harga'], 0, ',', '.') }}</td>
                        </tr>
                    @elseif ($invoiceData['status_reservasi'] == "Check out")
                        <tr>
                           
                            <th class="p-2 text-gray-900 font-bold pt-3">Total:</th>
                            <td class="p-2 text-red-600 font-bold text-right pt-3">Rp {{ number_format($invoiceData['total_harga'], 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t border-gray-200">
                            <th class="p-2 text-gray-600 text-sm font-normal">Denda:</th>
                            <td class="p-2 text-gray-900 font-semibold text-right">Rp {{ number_format($invoiceData['denda'], 0, ',', '.') }}</td>
                        </tr>
                    @else
                    <tr class="border-t border-gray-200">
                        <th class="p-2 text-gray-900 font-bold pt-3">Total:</th>
                        <td class="p-2 text-red-600 font-bold text-right pt-3">Rp {{ number_format($invoiceData['total_harga'], 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    
    </div>
</body>
</html>
