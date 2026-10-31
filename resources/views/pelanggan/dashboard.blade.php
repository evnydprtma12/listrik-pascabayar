@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="bg-abu-muda p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Selamat Datang, {{ $pelanggan->nama_pelanggan }}!</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Card Tagihan Terakhir --}}
        <div class="bg-green-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Tagihan Terakhir</div>
                @if($lastUnpaidTagihan)
                    <div class="text-2xl font-bold">Rp {{ number_format($lastUnpaidTagihan->total_tagihan, 2, ',', '.') }}</div>
                    <div class="text-sm">Bulan {{ $lastUnpaidTagihan->bulan }} {{ $lastUnpaidTagihan->tahun }}</div>
                    <div class="text-sm font-semibold mt-1">Status: {{ $lastUnpaidTagihan->status_tagihan }}</div>
                @else
                    <div class="text-2xl font-bold">Tidak Ada</div>
                    <div class="text-sm">Tagihan Belum Lunas</div>
                @endif
            </div>
            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

        {{-- Card Penggunaan Terakhir --}}
        <div class="bg-lime-500 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Penggunaan Terakhir</div>
                @if($lastPenggunaan)
                    <div class="text-2xl font-bold">{{ number_format($lastPenggunaan->jumlah_meter, 0, ',', '.') }} KWH</div>
                    <div class="text-sm">Bulan {{ $lastPenggunaan->bulan }} {{ $lastPenggunaan->tahun }}</div>
                    <div class="text-sm">Meter Awal: {{ number_format($lastPenggunaan->meter_awal, 0, ',', '.') }}</div>
                    <div class="text-sm">Meter Akhir: {{ number_format($lastPenggunaan->meter_akhir, 0, ',', '.') }}</div>
                @else
                    <div class="text-2xl font-bold">Belum Ada</div>
                    <div class="text-sm">Data Penggunaan</div>
                @endif
            </div>
            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>

        {{-- Card Info Sambungan --}}
        <div class="bg-emerald-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Info Sambungan</div>
                <div class="text-2xl font-bold">No. KWH: {{ $pelanggan->nomor_kwh }}</div>
                <div class="text-lg">Daya Terpasang: {{ optional($pelanggan->tarifs)->daya ?? 'N/A' }} VA</div>
            </div>
            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="mt-8">
        <div class="bg-gradient-to-r from-green-400 via-lime-300 to-green-500 p-1 rounded-xl shadow-xl">
            <div class="bg-white rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-green-800 mb-6 flex items-center">
                    <svg class="w-7 h-7 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m4 0h-1v4h-1m-4 0h-1v-4h-1"/>
                    </svg>
                    Akses Cepat
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Link ke Riwayat Penggunaan --}}
                    <a href="{{ route('pelanggan.riwayat_penggunaan') }}"
                       class="flex items-center bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 transition shadow group">
                        <svg class="w-8 h-8 text-green-500 mr-3 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 17a4 4 0 01-4-4V7a4 4 0 018 0v6a4 4 0 01-4 4zm0 0v2m0 0h2m-2 0H9"/>
                        </svg>
                        <span class="font-medium text-green-700 group-hover:underline">
                            Riwayat Penggunaan Listrik
                        </span>
                    </a>

                    {{-- Link ke Tagihan Saya --}}
                    <a href="{{ route('pelanggan.tagihan_saya') }}"
                       class="flex items-center bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg p-4 transition shadow group">
                        <svg class="w-8 h-8 text-emerald-500 mr-3 group-hover:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2a4 4 0 014-4h1a4 4 0 014 4v2m-7 0h7"/>
                        </svg>
                        <span class="font-medium text-emerald-700 group-hover:underline">
                            Semua Tagihan
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
