@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('title', 'Edit User') {{-- Judul halaman --}}

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-100 via-green-50 to-gray-100 py-12">
    <div class="w-full max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h1 class="text-4xl font-bold text-green-700 mb-6 text-center">Edit User</h1>

        {{-- Menampilkan pesan error validasi jika ada --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form untuk mengupdate user --}}
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf {{-- Token CSRF untuk keamanan --}}
            @method('PUT') {{-- Spoofing method PUT agar sesuai dengan RESTful Update --}}

            {{-- Input Nama User --}}
            <div class="mb-6">
                <label for="nama_user" class="block text-gray-700 text-base font-semibold mb-2">Nama User:</label>
                <input type="text" id="nama_user" name="nama_user"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700 text-base @error('nama_user') border-red-500 @enderror"
                    value="{{ old('nama_user', $user->nama_user) }}" required autofocus>
                @error('nama_user')
                    <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Username --}}
            <div class="mb-6">
                <label for="username" class="block text-gray-700 text-base font-semibold mb-2">Username:</label>
                <input type="text" id="username" name="username"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700 text-base @error('username') border-red-500 @enderror"
                    value="{{ old('username', $user->username) }}" required>
                @error('username')
                    <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Password (boleh dikosongkan jika tidak ingin diubah) --}}
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-base font-semibold mb-2">Password (kosongkan jika tidak diubah):</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700 text-base @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dropdown pilih level user --}}
            <div class="mb-6">
                <label for="level_id" class="block text-gray-700 text-base font-semibold mb-2">Level:</label>
                <select id="level_id" name="level_id"
                    class="w-full pr-10 py-3 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-700 text-base @error('level_id') border-red-500 @enderror"
                    required>
                    <option value="">-- Pilih Level --</option>
                    @foreach($levels as $level)
                        {{-- Menandai level yang sedang aktif --}}
                        <option value="{{ $level->id }}" {{ old('level_id', $user->level_id) == $level->id ? 'selected' : '' }}>
                            {{ $level->nama_level }}
                        </option>
                    @endforeach
                </select>
                @error('level_id')
                    <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol submit dan batal --}}
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800 font-semibold underline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
