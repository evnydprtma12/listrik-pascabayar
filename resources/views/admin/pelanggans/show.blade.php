@extends('layouts.app')

@section('title', 'Detail Pelanggan')
@section('page_title', 'Detail Pelanggan')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Informasi Pelanggan</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 font-semibold">Nama Pelanggan:</p>
                <p class="text-gray-800">{{ $pelanggan->nama_pelanggan }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Username:</p>
                <p class="text-gray-800">{{ $pelanggan->username }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Alamat:</p>
                <p class="text-gray-800">{{ $pelanggan->alamat }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Daya:</p>
                <p class="text-gray-800">{{ $pelanggan->daya }} VA</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Tarif per KWH:</p>
                <p class="text-gray-800">Rp {{ number_format($pelanggan->tarif->tarif_perkwh ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.pelanggan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali</a>
        </div>
    </div>
@endsection