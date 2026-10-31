@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-green-800 mb-6">Selamat Datang, Admin {{ Auth::guard('web')->user()->nama_user }}!</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Card Total Pelanggan --}}
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold">{{ number_format($totalPenggunaan, 0, ',', '.') }}</div>
                <div class="text-lg">Total Penggunaan</div>
            </div>
            <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-9H7v9M4 14h16V7H4v7" />
            </svg>
        </div>

        {{-- Card Tagihan Belum Lunas --}}
        <div class="bg-yellow-400 text-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold">{{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}</div>
                <div class="text-lg">Tagihan Belum Lunas</div>
            </div>
            <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Card Total Pembayaran Bulan Ini --}}
        <div class="bg-emerald-500 text-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold">Rp {{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</div>
                <div class="text-lg">Pembayaran Bulan Ini</div>
            </div>
            <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9v6m-4-4v4m-4-4v4m-4-4v4" />
            </svg>
        </div>
    </div>

    {{-- Riwayat Pembayaran --}}
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-green-800 mb-4">5 Pembayaran Terkini</h2>
        @forelse($latestPayments as $payment)
            <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                <div class="text-gray-700">
                    Pembayaran dari <strong class="font-medium">{{ $payment->pelanggan->nama_pelanggan ?? 'N/A' }}</strong>
                    untuk Tagihan bulan {{ $payment->tagihan->bulan ?? 'N/A' }} {{ $payment->tagihan->tahun ?? '' }}.
                </div>
                <div class="text-right">
                    <span class="text-green-600 font-semibold">Rp {{ number_format($payment->total_bayar, 2, ',', '.') }}</span>
                    <br>
                    <span class="text-gray-500 text-sm">{{ $payment->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <p class="text-gray-500 italic">Belum ada pembayaran yang tercatat.</p>
        @endforelse
    </div>
</div>
@endsection
