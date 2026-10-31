@extends('layouts.app') {{-- Menggunakan layout utama dari folder layouts --}}

@section('title', 'Tambah Tarif Listrik') {{-- Mengatur judul halaman --}}

@section('content')
{{-- Tambahan style langsung untuk background halaman --}}
<style>
    body {
        background: linear-gradient(to bottom right, #d1fae5, #f3f4f6); /* gradasi hijau ke abu-abu muda */
    }
</style>

<div class="min-h-screen flex items-center justify-center py-10 px-4">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-lg border border-green-200">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Tambah Tarif Baru</h1>

        {{-- Form input untuk menambah tarif baru --}}
        <form action="{{ route('admin.tarifs.store') }}" method="POST">
            @csrf {{-- Token keamanan CSRF wajib digunakan di semua form POST Laravel --}}

            {{-- Input daya listrik (VA) --}}
            <div class="mb-4">
                <label for="daya" class="block text-gray-700 text-sm font-semibold mb-2">Daya (VA):</label>
                <input type="number" id="daya" name="daya"
                    class="shadow-sm appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-300 @error('daya') border-red-500 @enderror"
                    value="{{ old('daya') }}" required>
                @error('daya')
                    {{-- Tampilkan pesan error validasi jika ada --}}
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input tarif per kWh --}}
            <div class="mb-6">
                <label for="tarif_perkwh" class="block text-gray-700 text-sm font-semibold mb-2">Tarif per kWh (Rp):</label>
                <input type="number" step="0.01" id="tarif_perkwh" name="tarif_perkwh"
                    class="shadow-sm appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-300 @error('tarif_perkwh') border-red-500 @enderror"
                    value="{{ old('tarif_perkwh') }}" required>
                @error('tarif_perkwh')
                    {{-- Tampilkan pesan error validasi jika ada --}}
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan dan Batal --}}
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                    Simpan Tarif
                </button>
                <a href="{{ route('admin.tarifs.index') }}"
                    class="text-sm text-gray-600 hover:text-green-600 font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
