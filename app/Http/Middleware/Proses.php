<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Diskon;
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
        $tanggalHariIni = Carbon::today();

        $diskon = Diskon::where('tanggal_berakhir','<' , $tanggalHariIni)->first();
        if ($diskon) {
            # code...
            $diskon->delete();
        }
        // dd($diskon);
        return $next($request);
    }
}
