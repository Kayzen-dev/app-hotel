<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan Crown Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { 
                font-size: 12px;
                background: white !important;
                -webkit-print-color-adjust: exact; 
            }
            .print-bg {
                background-color: #f8fafc !important;
            }
        }
        .watermark {
            opacity: 0.1;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
    </style>

<script>
    window.onload = function() {
        // Langsung trigger print tanpa delay
        window.print();
        
        // Set timeout untuk redirect setelah 1 detik
        const redirectTimer = setTimeout(() => {
            window.location.href = "{{ route('resepsionis.reservasi.data') }}";
        }, 1000);

        // Handler setelah print selesai
        window.onafterprint = function() {
            clearTimeout(redirectTimer); // Batalkan timeout jika print dialog sudah ditutup
            setTimeout(() => {
                window.location.href = "{{ route('resepsionis.reservasi.data') }}";
            }, 500); // Beri sedikit delay setelah menutup dialog print
        };
    };

    // Fallback untuk browser yang tidak support onafterprint
    window.addEventListener('afterprint', function() {
        window.location.href = "{{ route('resepsionis.reservasi.data') }}";
    });
</script>
</head>
<body class="bg-gray-50 p-8">
    <!-- Watermark -->
    <div class="watermark">
        <img src="{{ asset('images/logo_hotel.png') }}" class="h-64 w-64 opacity-20" alt="Watermark">
    </div>

    <!-- Container Utama -->
    <div class="max-w-4xl mx-auto rounded-lg overflow-hidden print-bg">
        <!-- Header -->
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-16 w-16 mr-4" src="{{ asset('images/logo_hotel.png') }}" alt="Logo Hotel">
                    <div>
                        <h1 class="text-2xl font-bold">Crown Hotel Pangandaran Syariah</h1>
                        <p class="text-sm text-gray-600 mt-1">
                            Jl Kidang Pananjung, Pangandaran No. 88, Jawa Barat<br>
                            Email: crownhotelpangandaran1@gmail.com<br>
                            Telp: (0858) 05362620
                        </p>
                    </div>
                </div>
            </div>
        </div>

        


        <!-- Periode Laporan -->
        <div class="p-6 ">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Laporan Pendapatan</h2>
                <p class="text-gray-600 mt-1">
                    Periode: {{ Carbon\Carbon::parse($start)->isoFormat('D MMMM Y') }} - {{ Carbon\Carbon::parse($end)->isoFormat('D MMMM Y') }}
                </p>
            </div>


          

            <!-- Ringkasan Kamar -->
<div class="mb-8">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 text-left text-sm text-gray-700 border-b-2 border-gray-200">Jenis Kamar</th>
                <th class="p-3 text-left text-sm text-gray-700 border-b-2 border-gray-200">Jumlah Kamar</th>
                <th class="p-3 text-right text-sm text-gray-700 border-b-2 border-gray-200">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
            // Filter reservasi yang status_reservasinya adalah 'selesai'
            $roomSummary = $data->filter(function($reservasi) {
                return $reservasi->status_reservasi == 'selesai';
            })
            ->flatMap->pesanan
            ->groupBy(function($item) {
                // Gabungkan tipe_kamar dan jenis_ranjang menjadi satu key yang unik
                $tipeKamar = $item->kamar->jenisKamar->tipe_kamar;
                $jenisRanjang = $item->kamar->jenisKamar->jenis_ranjang;
        
                return $tipeKamar . ' - ' . $jenisRanjang;  // Gabungkan tipe_kamar dan jenis_ranjang dengan tanda "-"
            })
            ->map(function($group, $key) {
                // Hitung total harga berdasarkan subtotal dan jumlah_kamar
                $totalHarga = $group->sum(function($item) {
                    return $item->subtotal * $item->jumlah_kamar;
                });
                
                return [
                    'tipe_kamar' => $key,  // Key yang sudah digabungkan (tipe_kamar-jenis_ranjang)
                    'count' => $group->sum('jumlah_kamar'),
                    'total' => $totalHarga
                ];
            });

            // Menghitung total keseluruhan pendapatan
            $totalPendapatan = $roomSummary->sum('total');
            @endphp
            
            @foreach($roomSummary as $summary)
            <tr class="hover:bg-gray-50 even:bg-gray-50">
                <td class="p-3 text-sm text-gray-700 border-b border-gray-100">{{ $summary['tipe_kamar'] }}</td>
                <td class="p-3 text-sm text-gray-700 border-b border-gray-100 text-left">{{ $summary['count'] }}</td>
                <td class="p-3 text-sm text-gray-700 border-b border-gray-100 text-right">Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Pendapatan -->
    <div class="mt-4 text-right font-semibold text-gray-800">
        <span>Total Pendapatan: </span>
        <span class="text-lg">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
    </div>

        <div class="mt-8 pt-4 border-t-2 border-gray-200">
            <div class="flex justify-end items-center">
                <div class="text-sm text-gray-700">
                    Manajement Hotel<br>
                    <br><br><br>
                    ---------------------

                </div>
            </div>
        </div>

</div>

            <!-- Footer -->
            <div class="mt-8 pt-4 border-t-2 border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Dicetak pada: {{ now()->isoFormat('D MMMM Y HH:mm:ss') }}
                    </div>
                 
                </div>
            </div>

            

        </div>
    </div>
</body>
</html>
