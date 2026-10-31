@extends('layouts.app')

@section('title', 'Manajemen Tagihan Listrik')

@section('content')
<div class="bg-gradient-to-tr from-green-100 via-green-50 to-green-200 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white w-full p-6 rounded-xl shadow-md overflow-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Daftar Tagihan Listrik</h1>

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

        {{-- Tombol Buat Tagihan --}}
        <div class="mb-4 text-right">
            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.tagihans.create_from_penggunaan') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                    + Buat Tagihan Dari Penggunaan
                </a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.tagihans.create_from_penggunaan') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                    + Buat Tagihan Dari Penggunaan
                </a>
            @endif
        </div>

        {{-- Tabel tagihan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-green-100 text-green-700">
                    <tr>
                        <th class="py-2 px-3 text-left border-b">No</th>
                        <th class="py-2 px-3 text-left border-b">Pelanggan</th>
                        <th class="py-2 px-3 text-left border-b">No. KWH</th>
                        <th class="py-2 px-3 text-left border-b">Bulan/Tahun</th>
                        <th class="py-2 px-3 text-left border-b">Meter</th>
                        <th class="py-2 px-3 text-left border-b">Total</th>
                        <th class="py-3 px-4 border-b bg-green-100 text-green-800 text-sm font-semibold text-center w-40">Status</th>
                        <th class="py-2 px-3 text-center border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihans as $tagihan)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3 border-b">{{ $loop->iteration + $tagihans->firstItem() - 1 }}</td>
                            <td class="py-2 px-3 border-b">{{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                            <td class="py-2 px-3 border-b">{{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                            <td class="py-2 px-3 border-b">{{ $tagihan->bulan }} {{ $tagihan->tahun }}</td>
                            <td class="py-2 px-3 border-b">{{ number_format($tagihan->jumlah_meter, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 border-b">Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</td>

                           <td class="py-2 px-4 border-b align-middle">
                                <div class="flex items-center justify-center h-full">
                                    <span class="inline-flex items-center justify-center text-center min-h-[2.25rem] px-3 py-1 text-xs font-semibold rounded-md
                                        {{ $tagihan->status_tagihan == 'Sudah Dibayar' 
                                            ? 'bg-green-200 text-green-900' 
                                            : 'bg-red-200 text-red-900' }}">
                                        {{ $tagihan->status_tagihan }}
                                    </span>
                                </div>
                            </td>


                            <td class="py-2 px-3 border-b text-center whitespace-nowrap">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    @if(Auth::guard('web')->user()->level_id == 1)
                                        <a href="{{ route('admin.tagihans.edit', $tagihan->id) }}"
                                           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.tagihans.destroy', $tagihan->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Hapus tagihan ini? Ini juga akan menghapus pembayaran terkait.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                                Hapus
                                            </button>
                                        </form>
                                    @elseif(Auth::guard('web')->user()->level_id == 2)
                                        <a href="{{ route('petugas.tagihans.edit', $tagihan->id) }}"
                                           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('petugas.tagihans.destroy', $tagihan->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Hapus tagihan ini? Ini juga akan menghapus pembayaran terkait.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">Belum ada data tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tagihans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $tagihans->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
            </div>
        @endif
    </div>
</div>
@endsection
