@extends('layouts.app')

@section('title', 'Manajemen Penggunaan Listrik')

@section('content')
<div class="bg-gradient-to-tr from-green-100 via-green-50 to-green-200 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white w-full p-6 rounded-xl shadow-md overflow-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Daftar Penggunaan Listrik</h1>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Notifikasi error --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tombol tambah penggunaan --}}
        <div class="mb-4 text-right">
            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.penggunaans.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                    + Tambah Penggunaan
                </a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.penggunaans.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                    + Tambah Penggunaan
                </a>
            @endif
        </div>

        {{-- Tabel data penggunaan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-green-100 text-green-700">
                    <tr>
                        <th class="py-2 px-3 text-left border-b">No</th>
                        <th class="py-2 px-3 text-left border-b">Pelanggan</th>
                        <th class="py-2 px-3 text-left border-b">No. KWH</th>
                        <th class="py-2 px-3 text-left border-b">Bulan/Tahun</th>
                        <th class="py-2 px-3 text-left border-b">Meter Awal</th>
                        <th class="py-2 px-3 text-left border-b">Meter Akhir</th>
                        <th class="py-2 px-3 text-left border-b">Jumlah Meter</th>
                        <th class="py-2 px-3 text-center border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penggunaans as $penggunaan)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3 border-b">{{ $loop->iteration + $penggunaans->firstItem() - 1 }}</td>
                            <td class="py-2 px-3 border-b">{{ $penggunaan->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td class="py-2 px-3 border-b">{{ $penggunaan->pelanggan->nomor_kwh ?? '-' }}</td>
                            <td class="py-2 px-3 border-b">{{ $penggunaan->bulan }} {{ $penggunaan->tahun }}</td>
                            <td class="py-2 px-3 border-b">{{ number_format($penggunaan->meter_awal) }}</td>
                            <td class="py-2 px-3 border-b">{{ number_format($penggunaan->meter_akhir) }}</td>
                            <td class="py-2 px-3 border-b">{{ number_format($penggunaan->jumlah_meter) }}</td>
                            <td class="py-2 px-3 border-b text-center whitespace-nowrap">
                                @if(Auth::guard('web')->user()->level_id == 1)
                                    <a href="{{ route('admin.penggunaans.edit', $penggunaan->id) }}"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded mr-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.penggunaans.destroy', $penggunaan->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penggunaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                @elseif(Auth::guard('web')->user()->level_id == 2)
                                    <a href="{{ route('petugas.penggunaans.edit', $penggunaan->id) }}"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded mr-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('petugas.penggunaans.destroy', $penggunaan->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penggunaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">Belum ada data penggunaan listrik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($penggunaans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $penggunaans->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
            </div>
        @endif
    </div>
</div>
@endsection
