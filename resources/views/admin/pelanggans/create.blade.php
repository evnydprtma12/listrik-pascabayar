@extends('layouts.app')

@section('title', 'Tambah Pelanggan Baru')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #e5fbee, #f3f4f6); /* abu muda ke hijau lembut */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg mx-auto border border-green-100">
    <h1 class="text-3xl font-extrabold text-green-700 mb-6 text-center">Tambah Pelanggan Baru</h1>

    <form action="{{ route('admin.pelanggans.store') }}" method="POST">
        @csrf

        {{-- Nama Pelanggan --}}
        <div class="mb-4">
            <label for="nama_pelanggan" class="block text-gray-700 font-medium mb-2">Nama Pelanggan:</label>
            <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('nama_pelanggan') border-red-500 @enderror" value="{{ old('nama_pelanggan') }}" required autofocus>
            @error('nama_pelanggan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Username --}}
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username:</label>
            <input type="text" id="username" name="username" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('username') border-red-500 @enderror" value="{{ old('username') }}" required>
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password:</label>
            <input type="password" id="password" name="password" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label for="alamat" class="block text-gray-700 font-medium mb-2">Alamat:</label>
            <textarea id="alamat" name="alamat" rows="3" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('alamat') border-red-500 @enderror" required>{{ old('alamat') }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor KWH --}}
        <div class="mb-4">
            <label for="nomor_kwh" class="block text-gray-700 font-medium mb-2">Nomor KWH:</label>
            <input type="text" id="nomor_kwh" name="nomor_kwh" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('nomor_kwh') border-red-500 @enderror" value="{{ old('nomor_kwh') }}" required>
            @error('nomor_kwh')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Pilih Tarif --}}
        <div class="mb-6">
            <label for="tarif_id" class="block text-gray-700 font-medium mb-2">Pilih Tarif:</label>
            <select id="tarif_id" name="tarif_id" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('tarif_id') border-red-500 @enderror" required>
                <option value="">-- Pilih Tarif --</option>
                @foreach($tarifs as $tarif)
                    <option value="{{ $tarif->id }}" {{ old('tarif_id') == $tarif->id ? 'selected' : '' }}>
                        {{ number_format($tarif->daya, 0, ',', '.') }} VA - Rp {{ number_format($tarif->tarif_perkwh, 2, ',', '.') }}/kWh
                    </option>
                @endforeach
            </select>
            @error('tarif_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-green-400">
                Simpan Pelanggan
            </button>
            <a href="{{ route('admin.pelanggans.index') }}" class="text-sm text-gray-600 hover:text-green-700 font-semibold">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
