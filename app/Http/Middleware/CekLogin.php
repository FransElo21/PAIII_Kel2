<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            // Menyimpan pesan error ke session
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu untuk melakukan pengajuan sewa.');
        }

        return $next($request);
    }

}
