<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk otorisasi akses bagi pengguna dengan peran Administrator.
 *
 * Memastikan hanya pengguna yang terautentikasi melalui guard 'web'
 * dan memiliki level_id 1 (Administrator) yang dapat mengakses rute tertentu.
 * Jika tidak memenuhi kriteria, pengguna akan di-logout dan diarahkan ke halaman login.
 *
 * @package App\Http\Middleware
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request  Objek Request yang masuk.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  Closure untuk melanjutkan request ke handler berikutnya.
     * @return \Symfony\Component\HttpFoundation\Response Respons dari request.
     */
public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa apakah pengguna terautentikasi melalui guard 'web'
        // DAN apakah level_id pengguna adalah 1 (Administrator).
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
            // Jika valid, lanjutkan request.
            return $next($request);
        }

        // Jika tidak valid, lakukan logout dan invalidasi sesi.
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mengarahkan pengguna ke halaman login dengan pesan error.
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai Admin.');
    }
}