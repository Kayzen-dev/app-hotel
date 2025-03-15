<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

                $user = User::find(Auth::id());

                if ($user && $user->status_login == 0) {
                    Auth::logout();
                    return redirect()->route('login')->with('message', 'Anda telah keluar Dari sesi');
                }else{
                     return $next($request);
                }
               

    }
}
