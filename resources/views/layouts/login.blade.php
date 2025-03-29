<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_hotel.png') }}">

    <title>Aplikasi Manajemen Hotel</title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-white dark:bg-gray-900">
    <section class="bg-white dark:bg-gray-900">

        <section class="lg:py-15 lg:flex lg:justify-center">
            <div
                class="overflow-hidden lg:mx-8 lg:flex lg:max-w-6xl lg:w-full  lg:rounded-xl mt-20">
                
                <div class="max-w-xl px-6 py-12 lg:max-w-5xl lg:w-1/2">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white md:text-3xl">
                        Aplikasi Manajemen <span class="text-blue-500">Hotel</span>
                    </h2>
        
                    <p class="mt-4 text-gray-500 dark:text-gray-300">
                        Aplikasi Manajemen Hotel ini dirancang untuk memudahkan pengelolaan operasional hotel, 
                        mulai dari proses reservasi, pencatatan data tamu, hingga pembuatan laporan keuangan. 
                        Dengan sistem ini, staf hotel dapat dengan mudah mengakses informasi terkait kamar yang tersedia, 
                        memproses pembayaran, dan mengelola riwayat tamu.
                    </p>
        
                </div>

                <div class="lg:w-1/2">
                         {{ $slot }}
                </div>
        
            </div>
        </section>



    </section>

    <footer class="bg-white dark:bg-gray-900">
        <div class="container px-6 py-8 mx-auto">
    
            <hr class="my-6 border-gray-200 md:my-10 dark:border-gray-700" />
    
            <div class="flex flex-col items-center sm:flex-row sm:justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-300">Crown Hotel Pangandaran Syariah | JL Kidang Pananjung No.88 Pangandaran, Jawa Barat</p>
            </div>
        </div>
    </footer>





</body>


</html>
