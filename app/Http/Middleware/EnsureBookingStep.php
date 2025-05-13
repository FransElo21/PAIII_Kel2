<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookingStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('booking_in_progress')) {
            return redirect()->route('search_welcomeProperty')
                ->with('error', 'Anda harus memilih properti terlebih dahulu.');
        }

        return $next($request);
    }
}
