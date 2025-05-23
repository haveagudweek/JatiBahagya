<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_verified) {
            Auth::logout();
            return redirect()->route('verify')
                ->with('email', $request->email)
                ->with('error', 'Anda perlu memverifikasi akun terlebih dahulu');
        }

        return $next($request);
    }
}
