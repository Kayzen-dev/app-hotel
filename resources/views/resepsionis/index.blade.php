<x-app-layout>
        @if(session('success'))
        <x-slot name="header">
            <div id="success-message" class="px-4 py-3 rounded mb-4">
                <div role="alert" class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </x-slot>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    const successMessage = document.getElementById('success-message');
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }
                }, 3000); // 3000ms = 3 detik
            });
        </script>
        @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 py-8">
                    <div class="flex flex-col justify-center items-center gap-2 border border-dashed border-gray-500 p-4 rounded-md h-32">
                        <div class="flex gap-2 items-center">
                            <span class="font-bold text-4xl">{{ $totalKamar }}</span>
                            <svg class="w-8 h-8 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M4 12h16M5 14h14M6 16h12M7 18h10" />
                            </svg>
                        </div>
                        <span class="font-semibold opacity-70 text-sm text-center">Total Kamar</span>
                    </div>
                
                    <div class="flex flex-col justify-center items-center gap-2 border border-dashed border-gray-500 p-4 rounded-md h-32">
                        <div class="flex gap-2 items-center">
                            <span class="font-bold text-4xl">{{ $totalReservasi }}</span>
                            <svg class="w-8 h-8 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 10h18M3 14h18M3 18h18" />
                            </svg>
                        </div>
                        <span class="font-semibold opacity-70 text-sm text-center">Total Reservasi</span>
                    </div>
                
                    <div class="flex flex-col justify-center items-center gap-2 border border-dashed border-gray-500 p-4 rounded-md h-32">
                        <div class="flex gap-2 items-center">
                            <span class="font-bold text-4xl">{{ $kamarDipesan }}</span>
                            <svg class="w-8 h-8 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18M3 12h18M3 15h18M3 18h18" />
                            </svg>
                        </div>
                        <span class="font-semibold opacity-70 text-sm text-center">Kamar Dipesan</span>
                    </div>
                
                    <div class="flex flex-col justify-center items-center gap-2 border border-dashed border-gray-500 p-4 rounded-md h-32">
                        <div class="flex gap-2 items-center">
                            <span class="font-bold text-4xl">{{ $kamarTersedia }}</span>
                            <svg class="w-8 h-8 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 10h18M3 13h18M3 16h18" />
                            </svg>
                        </div>
                        <span class="font-semibold opacity-70 text-sm text-center">Kamar Tersedia</span>
                    </div>
                </div>




                {{-- <div class="flex flex-col md:flex-row gap-4 p-4 h-screen">
                    <!-- Kiri (Dua Baris Div dengan Tinggi Setengah dari Kolom Kanan) -->
                    <div class="flex flex-col justify-between w-full md:w-2/3">
                        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg h-1/2">Baris 1</div>
                        <div class="h-4"></div> <!-- Jarak antara Baris 1 dan Baris 2 -->
                        <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg h-1/2">Baris 2</div>
                    </div>
                
                    <!-- Kanan (Kolom Penuh) -->
                    <div class="bg-gray-800 text-white p-6 rounded-lg shadow-lg w-full md:w-1/3 flex items-center justify-center">
                        Kolom Kanan
                    </div>
                </div> --}}
                
                
            </div>
        </div>
    </div>

</x-app-layout>
