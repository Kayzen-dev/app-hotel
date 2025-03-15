<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Page</title>
    @vite('resources/css/app.css') <!-- Pastikan ini menautkan Tailwind CSS -->
</head>
<body class="flex items-center justify-center min-h-screen bg-base-200">

    <div class="card w-full max-w-md shadow-xl bg-white p-6 rounded-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800">Anda Belum Memiliki Data Akun</h1>
        <p class="text-center text-gray-600 mt-2">
            Silakan hubungi administrator untuk mendapatkan akses lebih lanjut.
        </p>
        
        <div class="mt-4 flex justify-center">
            <a href="{{ route('login') }}" class="btn btn-primary">Kembali ke Login</a>
        </div>
    </div>

</body>
</html>