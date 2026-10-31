@extends('layouts.app')

{{-- Menentukan judul halaman --}}
@section('title', 'Edit Pembayaran')

@section('content')
<style>
    /* Styling untuk background halaman dan elemen input */
    body {
        background: linear-gradient(135deg, #d1fae5, #f0fdf4);
        min-height: 100vh;
    }

    input, select, textarea {
        font-size: 1rem;
    }

    label {
        font-size: 0.95rem;
    }

    .form-input {
        background-color: #f9fafb;
        border-color: #d1d5db;
    }
</style>

{{-- Kontainer utama form edit pembayaran --}}
<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-xl mx-auto mt-10 border border-green-100">
    <h1 class="text-3xl font-extrabold text-center text-green-700 mb-6">Edit Pembayaran</h1>

    {{-- Menampilkan pesan error dari session jika ada --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Menampilkan error validasi form --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form update pembayaran, URL disesuaikan berdasarkan level user (admin/petugas) --}}
    <form action="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.pembayarans.update', $pembayaran->id) : route('petugas.pembayarans.update', $pembayaran->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Spoofing method PUT untuk update --}}

        {{-- Menampilkan detail tagihan secara statis --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-1">Detail Tagihan:</label>
            <div class="bg-green-50 p-4 rounded-md border border-green-100 text-sm space-y-1">
                <p><strong>Pelanggan:</strong> {{ $pembayaran->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                <p><strong>No. KWH:</strong> {{ $pembayaran->pelanggan->nomor_kwh ?? 'N/A' }}</p>
                <p><strong>No. Tagihan:</strong> {{ $pembayaran->tagihan->id ?? 'N/A' }}</p>
                <p><strong>Periode:</strong> {{ ($pembayaran->tagihan->bulan ?? 'N/A') . ' ' . ($pembayaran->tagihan->tahun ?? '') }}</p>
                <p><strong>Total Tagihan Awal:</strong> Rp {{ number_format($pembayaran->tagihan->total_tagihan ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>

        {{-- Input tanggal pembayaran --}}
        <div class="mb-4">
            <label for="tanggal_pembayaran" class="block text-gray-700 font-semibold mb-1">Tanggal Pembayaran:</label>
            <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran" class="form-input w-full py-2 px-3 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 @error('tanggal_pembayaran') border-red-500 @enderror" value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran->format('Y-m-d')) }}" required>
            @error('tanggal_pembayaran')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Input biaya admin (optional) --}}
        <div class="mb-4">
            <label for="biaya_admin" class="block text-gray-700 font-semibold mb-1">Biaya Admin (Opsional):</label>
            <input type="number" step="0.01" id="biaya_admin" name="biaya_admin" class="form-input w-full py-2 px-3 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 @error('biaya_admin') border-red-500 @enderror" value="{{ old('biaya_admin', $pembayaran->biaya_admin) }}" min="0" oninput="updateTotalBayarDisplay()">
            @error('biaya_admin')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Menampilkan total yang harus dibayar setelah ditambah biaya admin --}}
        <div class="mb-6 bg-green-50 p-4 rounded-md border border-green-100">
            <p class="text-gray-700 font-semibold mb-1">Total yang Harus Dibayar (Termasuk Admin):</p>
            <p id="display_total_bayar" class="text-2xl font-extrabold text-green-700">Rp 0,00</p>
        </div>

        {{-- Tombol submit dan batal --}}
        <div class="flex justify-between items-center">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-lg transition duration-200">
                Update Pembayaran
            </button>
            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.pembayarans.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Batal</a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.pembayarans.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Batal</a>
            @endif
        </div>
    </form>
</div>

{{-- Script untuk menghitung dan menampilkan total bayar secara dinamis --}}
<script>
    // Fungsi format angka ke dalam bentuk rupiah
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    // Fungsi untuk memperbarui tampilan total bayar berdasarkan input biaya admin
    function updateTotalBayarDisplay() {
        const totalTagihan = parseFloat({{ $pembayaran->tagihan->total_tagihan ?? 0 }});
        const biayaAdmin = parseFloat(document.getElementById('biaya_admin').value || 0);
        const totalBayar = totalTagihan + biayaAdmin;
        document.getElementById('display_total_bayar').textContent = formatRupiah(totalBayar);
    }

    // Jalankan fungsi saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', () => {
        updateTotalBayarDisplay();
    });
</script>
@endsection
