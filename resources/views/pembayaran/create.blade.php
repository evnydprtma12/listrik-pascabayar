@extends('layouts.app')

@section('title', 'Catat Pembayaran Baru')

@section('content')
<style>
    body {
        background: linear-gradient(to right bottom, #d1fae5, #f0fdf4);
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-xl mx-auto mt-10 border border-green-200">
    <h1 class="text-3xl font-extrabold text-center text-green-700 mb-6">Catat Pembayaran Baru</h1>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.pembayarans.store') : route('petugas.pembayarans.store') }}" method="POST">
        @csrf

        {{-- Tagihan --}}
        <div class="mb-5">
            <label for="tagihan_id" class="block text-gray-700 font-semibold mb-2">Pilih Tagihan:</label>
            <select id="tagihan_id" name="tagihan_id" class="border border-gray-300 rounded-lg w-full py-3 px-4 focus:outline-none focus:ring-2 focus:ring-green-400 @error('tagihan_id') border-red-500 @enderror" required onchange="updateTotalTagihan()">
                <option class="text-center" value="">-- Pilih Tagihan Belum Lunas --</option>
                @foreach($tagihansBelumLunas as $tagihan)
                    <option value="{{ $tagihan->id }}"
                            data-total="{{ $tagihan->total_tagihan }}"
                            {{ old('tagihan_id') == $tagihan->id ? 'selected' : '' }}>
                        {{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }} (No. KWH: {{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}) - Bulan {{ $tagihan->bulan }} {{ $tagihan->tahun }} (Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }})
                    </option>
                @endforeach
            </select>
            @error('tagihan_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal --}}
        <div class="mb-5">
            <label for="tanggal_pembayaran" class="block text-gray-700 font-semibold mb-2">Tanggal Pembayaran:</label>
            <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran" class="border border-gray-300 rounded-lg w-full py-3 px-4 focus:outline-none focus:ring-2 focus:ring-green-400 @error('tanggal_pembayaran') border-red-500 @enderror" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" required>
            @error('tanggal_pembayaran')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Biaya Admin --}}
        <div class="mb-5">
            <label for="biaya_admin" class="block text-gray-700 font-semibold mb-2">Biaya Admin (Opsional):</label>
            <input type="number" step="0.01" id="biaya_admin" name="biaya_admin" min="0" class="border border-gray-300 rounded-lg w-full py-3 px-4 focus:outline-none focus:ring-2 focus:ring-green-400 @error('biaya_admin') border-red-500 @enderror" value="{{ old('biaya_admin', 0) }}" oninput="updateTotalBayarDisplay()">
            @error('biaya_admin')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Display Total --}}
        <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-lg">
            <p class="text-gray-700 font-semibold">Total Tagihan (Tanpa Admin):</p>
            <p id="display_total_tagihan" class="text-xl font-bold text-green-800">Rp 0,00</p>

            <p class="mt-4 text-gray-700 font-semibold">Total yang Harus Dibayar (Termasuk Admin):</p>
            <p id="display_total_bayar" class="text-2xl font-extrabold text-green-900">Rp 0,00</p>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200">
                Catat Pembayaran
            </button>
            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.pembayarans.index') }}" class="text-gray-600 hover:text-green-800 font-semibold text-sm">Batal</a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.pembayarans.index') }}" class="text-gray-600 hover:text-green-800 font-semibold text-sm">Batal</a>
            @endif
        </div>
    </form>
</div>

{{-- Script --}}
<script>
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    function updateTotalTagihan() {
        const tagihanSelect = document.getElementById('tagihan_id');
        const selectedOption = tagihanSelect.options[tagihanSelect.selectedIndex];
        const totalTagihan = parseFloat(selectedOption.dataset.total || 0);
        document.getElementById('display_total_tagihan').textContent = formatRupiah(totalTagihan);
        updateTotalBayarDisplay();
    }

    function updateTotalBayarDisplay() {
        const tagihanSelect = document.getElementById('tagihan_id');
        const selectedOption = tagihanSelect.options[tagihanSelect.selectedIndex];
        const totalTagihan = parseFloat(selectedOption.dataset.total || 0);
        const biayaAdmin = parseFloat(document.getElementById('biaya_admin').value || 0);
        const totalBayar = totalTagihan + biayaAdmin;
        document.getElementById('display_total_bayar').textContent = formatRupiah(totalBayar);
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateTotalTagihan();
    });
</script>
@endsection
