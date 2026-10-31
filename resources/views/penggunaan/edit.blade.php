@extends('layouts.app')

@section('title', 'Edit Penggunaan Listrik')

@section('content')
<style>
    /* Gaya latar belakang halaman */
    body {
        background: linear-gradient(to right, #d1fae5, #e0f2fe);
        min-height: 100vh;
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl mx-auto mt-10">
    <h1 class="text-4xl font-extrabold text-green-700 mb-8 text-center">Edit Penggunaan Listrik</h1>

    {{-- Form untuk update data penggunaan listrik --}}
    <form action="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.penggunaans.update', $penggunaan->id) : route('petugas.penggunaans.update', $penggunaan->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Menggunakan metode HTTP PUT untuk update data --}}

        {{-- Dropdown untuk memilih pelanggan --}}
        <div class="mb-5">
            <label for="pelanggan_id" class="block text-gray-700 font-semibold mb-2">Pilih Pelanggan:</label>
            <select id="pelanggan_id" name="pelanggan_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 @error('pelanggan_id') border-red-500 @enderror" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id', $penggunaan->pelanggan_id) == $pelanggan->id ? 'selected' : '' }}>
                        {{ $pelanggan->nama_pelanggan }} (No. KWH: {{ $pelanggan->nomor_kwh }})
                    </option>
                @endforeach
            </select>
            @error('pelanggan_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Form input untuk bulan dan tahun --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label for="bulan" class="block text-gray-700 font-semibold mb-2">Bulan:</label>
                <select id="bulan" name="bulan" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 @error('bulan') border-red-500 @enderror" required>
                    <option value="">-- Pilih Bulan --</option>
                    @php
                        // Daftar nama bulan
                        $bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @foreach($bulanNames as $bulanName)
                        <option value="{{ $bulanName }}" {{ old('bulan', $penggunaan->bulan) == $bulanName ? 'selected' : '' }}>{{ $bulanName }}</option>
                    @endforeach
                </select>
                @error('bulan')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tahun" class="block text-gray-700 font-semibold mb-2">Tahun:</label>
                <input type="number" id="tahun" name="tahun" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 @error('tahun') border-red-500 @enderror" value="{{ old('tahun', $penggunaan->tahun) }}" required min="2000" max="{{ date('Y') + 1 }}">
                @error('tahun')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Form input untuk meter awal dan akhir --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label for="meter_awal" class="block text-gray-700 font-semibold mb-2">Meter Awal:</label>
                <input type="number" id="meter_awal" name="meter_awal" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 @error('meter_awal') border-red-500 @enderror" value="{{ old('meter_awal', $penggunaan->meter_awal) }}" required min="0">
                @error('meter_awal')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="meter_akhir" class="block text-gray-700 font-semibold mb-2">Meter Akhir:</label>
                <input type="number" id="meter_akhir" name="meter_akhir" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 @error('meter_akhir') border-red-500 @enderror" value="{{ old('meter_akhir', $penggunaan->meter_akhir) }}" required min="0">
                @error('meter_akhir')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tombol submit dan batal --}}
        <div class="flex items-center justify-between mt-8">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-200">
                Simpan Perubahan
            </button>
            <a href="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.penggunaans.index') : route('petugas.penggunaans.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
