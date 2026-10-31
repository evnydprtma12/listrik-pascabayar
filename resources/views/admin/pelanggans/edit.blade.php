@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-100 via-abu-muda to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-xl w-full max-w-2xl mx-auto border border-green-200">
        <h1 class="text-3xl font-extrabold text-green-700 mb-8 text-center tracking-wide">Edit Data Pelanggan</h1>

        <form action="{{ route('admin.pelanggans.update', $pelanggan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                {{-- Nama --}}
                <div>
                    <label for="nama_pelanggan" class="block text-gray-700 font-semibold mb-1">Nama Pelanggan</label>
                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 @error('nama_pelanggan') border-red-500 @enderror" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required autofocus>
                    @error('nama_pelanggan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-gray-700 font-semibold mb-1">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 @error('username') border-red-500 @enderror" value="{{ old('username', $pelanggan->username) }}" required>
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-gray-700 font-semibold mb-1">Password (opsional)</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-gray-700 font-semibold mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 @error('alamat') border-red-500 @enderror" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor KWH --}}
                <div>
                    <label for="nomor_kwh" class="block text-gray-700 font-semibold mb-1">Nomor KWH</label>
                    <input type="text" id="nomor_kwh" name="nomor_kwh" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 @error('nomor_kwh') border-red-500 @enderror" value="{{ old('nomor_kwh', $pelanggan->nomor_kwh) }}" required>
                    @error('nomor_kwh')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih Tarif --}}
                <div>
                    <label for="tarif_id" class="block text-gray-700 font-semibold mb-1">Pilih Tarif</label>
                    <select id="tarif_id" name="tarif_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 @error('tarif_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Tarif --</option>
                        @foreach($tarifs as $tarif)
                            <option value="{{ $tarif->id }}" {{ old('tarif_id', $pelanggan->tarif_id) == $tarif->id ? 'selected' : '' }}>
                                {{ number_format($tarif->daya, 0, ',', '.') }} VA - Rp {{ number_format($tarif->tarif_perkwh, 2, ',', '.') }}/kWh
                            </option>
                        @endforeach
                    </select>
                    @error('tarif_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="mt-8 flex justify-between items-center">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg shadow transition duration-200">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.pelanggans.index') }}" class="text-gray-600 hover:underline hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
