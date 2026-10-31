<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk mengelola autentikasi user (admin, petugas, pelanggan).
 *
 * @package App\Http\Controllers\Auth
 */
class UserAuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login untuk admin, petugas, atau pelanggan.
     * Menggunakan guard 'web' dan 'pelanggan'.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Logout dari semua guard terlebih dahulu
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Login sebagai admin/petugas
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            
            /** @var \App\Models\User $user */
            $user = Auth::guard('web')->user();
            $user->load('level');


            if ($user->level_id == 1) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->level_id == 2) {
                return redirect()->route('petugas.dashboard');
            }

            return redirect()->route('admin.dashboard');
        }

        // Login sebagai pelanggan
        if (Auth::guard('pelanggan')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('pelanggan.dashboard');
        }

        // Gagal login
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Tampilkan halaman registrasi untuk pelanggan baru.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $tarifs = Tarif::orderBy('daya')->get();
        return view('auth.register', compact('tarifs'));
    }

    /**
     * Proses registrasi pelanggan baru.
     * Setelah registrasi, pelanggan langsung login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'username' => ['required', 'string', 'max:100', 'unique:pelanggan,username'],
            'password' => 'required|string|min:6|confirmed',
            'alamat' => 'required|string|max:200',
            'nomor_kwh' => ['required', 'string', 'max:50', 'unique:pelanggan,nomor_kwh'],
            'tarif_id' => 'required|exists:tarifs,id',
        ], [
            'nama_pelanggan.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'alamat.required' => 'Alamat wajib diisi.',
            'nomor_kwh.required' => 'Nomor KWH wajib diisi.',
            'nomor_kwh.unique' => 'Nomor KWH ini sudah terdaftar.',
            'tarif_id.required' => 'Tarif listrik wajib dipilih.',
            'tarif_id.exists' => 'Tarif listrik tidak valid.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $pelanggan = Pelanggan::create($validated);
        Auth::guard('pelanggan')->login($pelanggan);

        return redirect()->route('pelanggan.dashboard')
                         ->with('success', 'Registrasi berhasil! Selamat datang, ' . $pelanggan->nama_pelanggan);
    }

    /**
     * Logout dari semua guard yang sedang aktif (web/pelanggan).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
