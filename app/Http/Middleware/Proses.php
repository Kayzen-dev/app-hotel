<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Diskon;
use App\Models\Harga;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Proses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengambil tanggal hari ini
            $tanggalHariIni = Carbon::today(); // atau bisa menggunakan Carbon::now() untuk waktu lebih tepat

            // Menemukan diskon yang sudah lewat tanggal berakhirnya dan tidak memiliki relasi ke pesanan
            $diskon = Diskon::where('tanggal_berakhir', '<', $tanggalHariIni)
                        ->get();

            // Mengecek apakah ada diskon yang sudah lewat tanggal berakhirnya dan tidak ada relasi pesanan
            if ($diskon->isNotEmpty()) {
                // Menghapus semua diskon yang sudah lewat dan tidak ada relasi pesanan
                $diskon->each(function($item) {
                    $item->delete();
                });
            }

        
        // Menemukan diskon yang sudah lewat tanggal berakhirnya
      // Menemukan harga yang sudah lewat tanggal berakhirnya dan tidak memiliki relasi ke pesanan
        $harga = Harga::where('tanggal_berakhir', '<', $tanggalHariIni)
        ->get();

        // Mengecek apakah ada harga yang sudah lewat tanggal berakhirnya dan tidak ada relasi pesanan
        if ($harga->isNotEmpty()) {
        // Menghapus semua harga yang sudah lewat dan tidak ada relasi pesanan
            $harga->each(function($item) {
            $item->delete();
            });
        }


        // Melanjutkan request
        return $next($request);
    }
}
