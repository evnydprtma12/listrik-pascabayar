@extends('layouts.app')

@section('title', 'Tambah Penggunaan Listrik')

@section('content')
<style>
    body {
        background: linear-gradient(to bottom right, #d1fae5, #f3f4f6); /* Hijau daun ke abu muda */
    }
</style>

<div class="min-h-screen flex items-center justify-center py-10 px-4">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-lg border border-green-200">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Tambah Penggunaan Listrik Baru</h1>

        <form action="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.penggunaans.store') : route('petugas.penggunaans.store') }}" method="POST">
            @csrf

            {{-- Pilih Pelanggan --}}
            <div class="mb-4">
                <label for="pelanggan_id" class="block text-gray-700 text-sm font-semibold mb-2">Pilih Pelanggan:</label>
                <select id="pelanggan_id" name="pelanggan_id" class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('pelanggan_id') border-red-500 @enderror" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                            {{ $pelanggan->nama_pelanggan }} (No. KWH: {{ $pelanggan->nomor_kwh }})
                        </option>
                    @endforeach
                </select>
                @error('pelanggan_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bulan dan Tahun --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="bulan" class="block text-gray-700 text-sm font-semibold mb-2">Bulan:</label>
                    <select id="bulan" name="bulan" class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('bulan') border-red-500 @enderror" required>
                        <option value="">-- Pilih Bulan --</option>
                        @php
                            $bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        @endphp
                        @foreach($bulanNames as $bulanName)
                            <option value="{{ $bulanName }}" {{ old('bulan') == $bulanName ? 'selected' : '' }}>{{ $bulanName }}</option>
                        @endforeach
                    </select>
                    @error('bulan')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tahun" class="block text-gray-700 text-sm font-semibold mb-2">Tahun:</label>
                    <input type="number" id="tahun" name="tahun" class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('tahun') border-red-500 @enderror" value="{{ old('tahun', date('Y')) }}" required min="2000" max="{{ date('Y') + 1 }}">
                    @error('tahun')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Meter Awal --}}
            <div class="mb-4">
                <label for="meter_awal" class="block text-gray-700 text-sm font-semibold mb-2">Meter Awal:</label>
                <input type="number" id="meter_awal" name="meter_awal" class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('meter_awal') border-red-500 @enderror" value="{{ old('meter_awal') }}" required min="0">
                @error('meter_awal')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Meter Akhir --}}
            <div class="mb-6">
                <label for="meter_akhir" class="block text-gray-700 text-sm font-semibold mb-2">Meter Akhir:</label>
                <input type="number" id="meter_akhir" name="meter_akhir" class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('meter_akhir') border-red-500 @enderror" value="{{ old('meter_akhir') }}" required min="0">
                @error('meter_akhir')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                    Simpan Penggunaan
                </button>
                @if(Auth::guard('web')->user()->level_id == 1)
                    <a href="{{ route('admin.penggunaans.index') }}" class="text-sm text-gray-600 hover:text-green-600 font-semibold">Batal</a>
                @elseif(Auth::guard('web')->user()->level_id == 2)
                    <a href="{{ route('petugas.penggunaans.index') }}" class="text-sm text-gray-600 hover:text-green-600 font-semibold">Batal</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
