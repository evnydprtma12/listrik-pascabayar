@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg mx-auto">
    <!-- Judul halaman -->
    <h1 class="text-3xl font-bold text-green-800 mb-6 text-center">Profil Saya</h1>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Menampilkan daftar error validasi jika ada -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form untuk mengupdate profil -->
    <form action="{{ route('pelanggan.update_profil') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Input nama pelanggan -->
        <div class="mb-4">
            <label for="nama_pelanggan" class="block text-green-800 text-sm font-semibold mb-2">Nama Lengkap:</label>
            <input type="text" id="nama_pelanggan" name="nama_pelanggan"
                class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('nama_pelanggan') border-red-500 @enderror"
                value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
            @error('nama_pelanggan')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input username -->
        <div class="mb-4">
            <label for="username" class="block text-green-800 text-sm font-semibold mb-2">Username:</label>
            <input type="text" id="username" name="username"
                class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('username') border-red-500 @enderror"
                value="{{ old('username', $pelanggan->username) }}" required>
            @error('username')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input alamat pelanggan -->
        <div class="mb-4">
            <label for="alamat" class="block text-green-800 text-sm font-semibold mb-2">Alamat:</label>
            <textarea id="alamat" name="alamat" rows="3"
                class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('alamat') border-red-500 @enderror"
                required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Informasi yang tidak bisa diubah oleh user -->
        <div class="mb-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
            <h3 class="text-base font-semibold text-green-700 mb-2">Informasi Lain (Tidak Dapat Diubah):</h3>
            <p class="text-gray-700 mb-1"><strong>Nomor KWH:</strong> {{ $pelanggan->nomor_kwh }}</p>
            <p class="text-gray-700"><strong>Daya Terpasang:</strong> {{ optional($pelanggan->tarifs)->daya ?? 'N/A' }} VA</p>
        </div>

        <!-- Input password baru (opsional) -->
        <div class="mb-4">
            <label for="password" class="block text-green-800 text-sm font-semibold mb-2">Password Baru (opsional):</label>
            <input type="password" id="password" name="password"
                class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input konfirmasi password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-green-800 text-sm font-semibold mb-2">Konfirmasi Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300">
        </div>

        <!-- Tombol simpan dan batal -->
        <div class="flex items-center justify-between">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                Simpan Perubahan
            </button>
            <a href="{{ route('pelanggan.dashboard') }}"
                class="text-sm font-semibold text-gray-600 hover:text-green-700 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
