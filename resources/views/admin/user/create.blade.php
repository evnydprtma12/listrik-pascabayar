{{-- Menggunakan layout utama dari folder layouts --}}
@extends('layouts.app')

{{-- Menentukan judul halaman --}}
@section('title', 'Tambah User Baru')

{{-- Bagian konten utama halaman --}}
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 via-green-50 to-gray-200 py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
        {{-- Judul Form --}}
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Tambah User Baru</h1>

        {{-- Menampilkan pesan error jika validasi gagal --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-3 list-disc list-inside">
                    {{-- Menampilkan semua error dalam bentuk list --}}
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form tambah user baru --}}
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf {{-- Token CSRF untuk keamanan form --}}

            {{-- Input nama user --}}
            <div class="mb-4">
                <label for="nama_user" class="block text-green-900 text-sm font-semibold mb-2">Nama User:</label>
                <input type="text" id="nama_user" name="nama_user"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 @error('nama_user') border-red-500 @enderror"
                    value="{{ old('nama_user') }}" required autofocus>
                {{-- Menampilkan error jika ada --}}
                @error('nama_user')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input username --}}
            <div class="mb-4">
                <label for="username" class="block text-green-900 text-sm font-semibold mb-2">Username:</label>
                <input type="text" id="username" name="username"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 @error('username') border-red-500 @enderror"
                    value="{{ old('username') }}" required>
                @error('username')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input password --}}
            <div class="mb-4">
                <label for="password" class="block text-green-900 text-sm font-semibold mb-2">Password:</label>
                <input type="password" id="password" name="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 @error('password') border-red-500 @enderror"
                    required>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Select Level --}}
            <div class="mb-6">
                <label for="level_id" class="block text-green-900 text-sm font-semibold mb-2">Level:</label>
                <select id="level_id" name="level_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-300 @error('level_id') border-red-500 @enderror"
                    required>
                    <option value="">-- Pilih Level --</option>
                    {{-- Menampilkan semua level dari controller --}}
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->nama_level }}
                        </option>
                    @endforeach
                </select>
                @error('level_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol submit dan batal --}}
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                    Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
