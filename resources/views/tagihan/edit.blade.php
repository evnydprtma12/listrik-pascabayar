@extends('layouts.app')

@section('title', 'Edit Status Tagihan')

@section('content')
<style>
    body {
        background: linear-gradient(to bottom right, #d1fae5, #e5e7eb);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>

<div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg mx-auto mt-10 border border-gray-200">
    <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Edit Status Tagihan</h1>

    <form action="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.tagihans.update', $tagihan->id) : route('petugas.tagihans.update', $tagihan->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Detail Tagihan --}}
        <div class="mb-6">
            <label class="block text-gray-800 text-sm font-semibold mb-2">Detail Tagihan:</label>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100 text-gray-800">
                <p><strong>Pelanggan:</strong> {{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                <p><strong>No. KWH:</strong> {{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</p>
                <p><strong>Periode:</strong> {{ $tagihan->bulan }} {{ $tagihan->tahun }}</p>
                <p><strong>Jumlah Meter:</strong> {{ number_format($tagihan->jumlah_meter, 0, ',', '.') }} KWH</p>
                <p><strong>Daya/Tarif:</strong>
                    @if($tagihan->tarif_data)
                        {{ number_format($tagihan->tarif_data->daya, 0, ',', '.') }} VA (Rp {{ number_format($tagihan->tarif_data->tarif_perkwh, 2, ',', '.') }}/kWh)
                    @else
                        N/A
                    @endif
                </p>
                <p class="mt-3 text-lg font-semibold text-green-800">
                    <strong>Total Tagihan:</strong> Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Pilih Status --}}
        <div class="mb-6">
            <label for="status_tagihan" class="block text-gray-800 text-sm font-semibold mb-2">Status Tagihan:</label>
            <select id="status_tagihan" name="status_tagihan" class="border border-gray-300 rounded-lg w-full px-4 py-2 focus:ring-2 focus:ring-green-300 focus:outline-none @error('status_tagihan') border-red-500 @enderror" required>
                <option value="Belum Dibayar" {{ old('status_tagihan', $tagihan->status_tagihan) == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="Sudah Dibayar" {{ old('status_tagihan', $tagihan->status_tagihan) == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
            </select>
            @error('status_tagihan')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                Update Status
            </button>

            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.tagihans.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Batal</a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.tagihans.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Batal</a>
            @endif
        </div>
    </form>
</div>
@endsection
