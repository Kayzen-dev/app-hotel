<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
        }
    </style>
</head>

<body class="bg-gray-100 p-4 h-screen flex items-center justify-center">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <img class="h-20 w-20 mr-2" src="{{ asset('images/logo_hotel.png') }}" alt="Logo" />
                <div class="text-gray-700 font-semibold text-lg">Hotel Crown Pangandaran Syariah</div>
            </div>
            <div class="text-gray-700 text-right">
                <div class="font-bold text-xl mb-2">INVOICE</div>
                <div class="text-sm">Tanggal: {{ $invoiceDate }}</div>
                <div class="text-sm">Invoice #: {{ $invoiceNumber }}</div>
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
                    {{ $namaTamu }}<br>
                    {{ $alamatTamu }}, {{ $kotaTamu }}<br>
                    {{ $emailTamu }}<br>
                    No. Telepon: {{ $teleponTamu }}
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
                @foreach ($reservasi as $item)
                <tr class="text-center">
                    <td class="p-2 text-gray-700">KING - TWIN</td>
                    <td class="p-2 text-gray-700">KING</td>
                    <td class="p-2 text-gray-700">3 Malam</td>
                    <td class="p-2 text-gray-700">Rp 40000.0</td>
                    <td class="p-2 text-gray-700">Rp 7000000.0</td>
                    <td class="p-2 text-gray-700">Rp 900000</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    
        <div class="flex justify-end mt-6">
            <table class="w-1/2 text-right">
                <tr>
                    <th class="p-2 text-gray-700">Subtotal:</th>
                    <td class="p-2 text-gray-900 font-bold">Rp 80000000</td>
                </tr>
                {{-- <tr>
                    <th class="p-2 text-gray-700">Pajak (10%):</th>
                    <td class="p-2 text-gray-900 font-bold">Rp{{ number_format($totalHarga * 0.1, 0, ',', '.') }}</td>
                </tr> --}}
                <tr>
                    <th class="p-2 text-gray-900 text-lg">Total:</th>
                    {{-- <td class="p-2 text-red-600 text-lg font-bold">Rp{{ number_format($totalHarga * 1.1, 0, ',', '.') }}</td> --}}
                    <td class="p-2 text-red-600 text-lg font-bold">Rp 90000</td>
                </tr>
            </table>
        </div>
    
    </div>

</body>
</html>
