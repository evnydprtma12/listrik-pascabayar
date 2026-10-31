@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="bg-gradient-to-tr from-green-100 via-green-50 to-green-200 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    {{-- Wrapper utama konten dengan padding responsif --}}

    <div class="bg-white w-full p-6 rounded-xl shadow-md overflow-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Daftar Pembayaran</h1>

        {{-- Notifikasi pesan sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Notifikasi pesan error --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tombol catat pembayaran baru --}}
        <div class="mb-4 text-right">
            @if(Auth::guard('web')->user()->level_id == 1)
                <a href="{{ route('admin.pembayarans.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                    + Catat Pembayaran
                </a>
            @elseif(Auth::guard('web')->user()->level_id == 2)
                <a href="{{ route('petugas.pembayarans.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                    + Catat Pembayaran
                </a>
            @endif
        </div>

        {{-- Tabel data pembayaran --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-green-100 text-green-700">
                    <tr>
                        <th class="py-2 px-3 text-left border-b">Pelanggan</th>
                        <th class="py-2 px-3 text-left border-b">No. Tagihan</th>
                        <th class="py-2 px-3 text-left border-b">Periode</th>
                        <th class="py-2 px-3 text-left border-b">Total Tagihan</th>
                        <th class="py-2 px-3 text-left border-b">Biaya Admin</th>
                        <th class="py-2 px-3 text-left border-b">Total Bayar</th>
                        <th class="py-2 px-3 text-left border-b">Tanggal Bayar</th>
                        <th class="py-2 px-3 text-left border-b">Dicatat Oleh</th>
                        <th class="py-2 px-3 text-center border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $pembayaran)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3 border-b">{{ $pembayaran->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                            <td class="py-2 px-3 border-b">{{ $pembayaran->tagihan->id ?? 'N/A' }}</td>
                            <td class="py-2 px-3 border-b">{{ ($pembayaran->tagihan->bulan ?? '-') . ' ' . ($pembayaran->tagihan->tahun ?? '-') }}</td>
                            <td class="py-2 px-3 border-b">Rp {{ number_format($pembayaran->tagihan->total_tagihan ?? 0, 2, ',', '.') }}</td>
                            <td class="py-2 px-3 border-b">Rp {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</td>
                            <td class="py-2 px-3 border-b">Rp {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</td>
                            <td class="py-2 px-3 border-b">{{ $pembayaran->tanggal_pembayaran->format('d M Y') }}</td>
                            <td class="py-2 px-3 border-b">{{ $pembayaran->user->nama_user ?? 'Pelanggan' }}</td>
                            <td class="py-2 px-3 border-b text-center whitespace-nowrap">
                                {{-- Tombol Aksi --}}
                                @if(Auth::guard('web')->user()->level_id == 1)
                                    <a href="{{ route('admin.pembayarans.edit', $pembayaran->id) }}"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded mr-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.pembayarans.destroy', $pembayaran->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus pembayaran ini? Tagihan akan kembali menjadi Belum Lunas.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                @elseif(Auth::guard('web')->user()->level_id == 2)
                                    <a href="{{ route('petugas.pembayarans.edit', $pembayaran->id) }}"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded mr-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('petugas.pembayarans.destroy', $pembayaran->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus pembayaran ini? Tagihan akan kembali menjadi Belum Lunas.')">
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
                            <td colspan="9" class="text-center py-4 text-gray-500">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Komponen Pagination --}}
        @if ($pembayarans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $pembayarans->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
            </div>
        @endif
    </div>
</div>
@endsection
