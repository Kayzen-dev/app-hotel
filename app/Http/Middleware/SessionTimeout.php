<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    protected $timeout = 1100;


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            
            $lastActivity = session('last_activity');
            $currentTime = now()->timestamp;

            if ($lastActivity && ($currentTime - $lastActivity) > $this->timeout) {
                $user = User::findOrFail(Auth::id());

                if ($user) {
                    $user->update(['status_login' => false]);
                }

                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->route('login')->with('message', 'Anda telah keluar karena tidak ada aktivitas.');
            }

            session(['last_activity' => $currentTime]);
        }

        return $next($request);
    }
}
