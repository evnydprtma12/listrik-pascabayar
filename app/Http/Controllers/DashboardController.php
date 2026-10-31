<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Penggunaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk Administrator.
     */
    public function adminDashboard()
    {
        $totalPenggunaan = Penggunaan::count();
        $totalTagihanBelumLunas = Tagihan::where('status_tagihan', 'Belum Dibayar')->count();
        $totalPembayaranBulanIni = Pembayaran::whereMonth('tanggal_pembayaran', now()->month)
                                            ->whereYear('tanggal_pembayaran', now()->year)
                                            ->sum('total_bayar');

        // Aktivitas Terkini untuk Admin (misalnya, 5 pembayaran terbaru)
        $latestPayments = Pembayaran::with(['pelanggan', 'tagihan']) // Load relasi yang dibutuhkan
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5) // Ambil 5 data terbaru
                                    ->get();

        return view('admin.dashboard', compact(
            'totalPenggunaan',
            'totalTagihanBelumLunas',
            'totalPembayaranBulanIni',
            'latestPayments' // Tambahkan variabel ini
        ));
    }

    /**
     * Menampilkan dashboard untuk Petugas.
     */
    public function petugasDashboard()
    {
        $totalPelanggan = Pelanggan::count();
        $totalTagihanBelumLunas = Tagihan::where('status_tagihan', 'Belum Dibayar')->count();
        $totalPenggunaanBulanIni = Penggunaan::whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year)
                                            ->sum(DB::raw('meter_akhir - meter_awal'));

        // Aktivitas Terkini untuk Petugas (misalnya, 5 penggunaan terbaru)
        $latestPenggunaans = Penggunaan::with('pelanggan') // Load relasi pelanggan
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5) // Ambil 5 data terbaru
                                        ->get();

        return view('petugas.dashboard', compact(
            'totalPelanggan',
            'totalTagihanBelumLunas',
            'totalPenggunaanBulanIni',
            'latestPenggunaans' // Tambahkan variabel ini
        ));
    }
}