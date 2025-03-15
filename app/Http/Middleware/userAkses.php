<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class userAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        if (Auth::check()) {
            $user = User::find(Auth::id());
            if ($user->hasRole('pemilik')) {
                return redirect()->route('pemilik');
            } elseif ($user->hasRole('resepsionis')) {
                return redirect()->route('resepsionis');
            }else{
                return $next($request);
            }   
        }else{
            return $next($request);
        } 
        


    }
}
