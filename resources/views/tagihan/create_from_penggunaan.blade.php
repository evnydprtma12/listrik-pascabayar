@extends('layouts.app')

@section('title', 'Buat Tagihan Baru')

@section('content')
<style>
    /* Styling background dan layout utama */
    body {
        background: linear-gradient(135deg, #d1fae5, #f3f4f6);
        min-height: 100vh;
    }

    /* Kotak utama form */
    .custom-box {
        background-color: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 800px;
        margin: 2rem auto;
    }

    /* Kotak checkbox untuk data penggunaan */
    .checkbox-container {
        background-color: #f0fdf4;
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #bbf7d0;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .checkbox-container input[type="checkbox"] {
        transform: scale(1.2);
    }

    .checkbox-container label {
        margin-left: 0.75rem;
        font-size: 1rem;
        color: #065f46;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #4ade80;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 0.375rem;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #22c55e;
    }

    .btn-cancel {
        color: #374151;
        font-weight: bold;
        font-size: 0.875rem;
    }

    .section-title {
        color: #064e3b;
    }
</style>

<div class="custom-box">
    <h1 class="text-3xl font-bold section-title mb-6 text-center">Buat Tagihan dari Penggunaan</h1>

    {{-- Menampilkan notifikasi sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Menampilkan notifikasi error --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Menampilkan validasi error dari input --}}
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

    {{-- Penjelasan untuk pengguna --}}
    <p class="mb-4 text-gray-700">Pilih data penggunaan listrik yang ingin Anda buat tagihannya. Hanya data penggunaan yang belum memiliki tagihan yang akan ditampilkan.</p>

    {{-- Form submit berdasarkan level user (admin atau petugas) --}}
    <form action="{{ Auth::guard('web')->user()->level_id == 1 ? route('admin.tagihans.generate') : route('petugas.tagihans.generate') }}" method="POST">
        @csrf

        {{-- Daftar penggunaan listrik yang belum ditagih --}}
        <div class="overflow-x-auto mb-6">
            @forelse($penggunaansBelumDitagih as $penggunaan)
                <div class="checkbox-container">
                    <input type="checkbox" name="penggunaan_ids[]" value="{{ $penggunaan->id }}" id="penggunaan-{{ $penggunaan->id }}">
                    <label for="penggunaan-{{ $penggunaan->id }}">
                        <span class="font-semibold">{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }}</span>
                        (No. KWH: {{ $penggunaan->pelanggan->nomor_kwh ?? 'N/A' }}) -
                        Penggunaan Bulan {{ $penggunaan->bulan }} {{ $penggunaan->tahun }} ({{ $penggunaan->meter_awal }} - {{ $penggunaan->meter_akhir }})
                    </label>
                </div>
            @empty
                {{-- Jika tidak ada data yang bisa ditagih --}}
                <p class="text-center text-gray-500 py-4">Tidak ada data penggunaan yang belum ditagih.</p>
            @endforelse
        </div>

        {{-- Tombol submit dan tombol batal sesuai level user --}}
        <div class="flex justify-between items-center">
            <button type="submit"
                    class="btn-primary"
                    @if($penggunaansBelumDitagih->isEmpty()) disabled @endif>
                Generate Tagihan
            </button>

            {{-- Tombol batal redirect ke halaman sesuai level --}}
            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.tagihans.index') }}" class="btn-cancel">Batal</a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.tagihans.index') }}" class="btn-cancel">Batal</a>
            @endif
        </div>
    </form>
</div>
@endsection
