@extends('layouts.app')

@section('title', 'Manajemen Pelanggan Admin')

@section('content')
<div class="bg-gradient-to-tr from-green-100 via-green-50 to-green-200 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white w-full p-6 rounded-xl shadow-md overflow-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Daftar Pelanggan</h1>

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

        {{-- Tombol Tambah --}}
        <div class="mb-4 text-right">
            <a href="{{ route('admin.pelanggans.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                + Tambah Pelanggan Baru
            </a>
        </div>

        {{-- Tabel Pelanggan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-green-100 text-green-700">
                    <tr>
                        <th class="py-2 px-3 text-left border-b">No</th>
                        <th class="py-2 px-3 text-left border-b">Nama Pelanggan</th>
                        <th class="py-2 px-3 text-left border-b">Username</th>
                        <th class="py-2 px-3 text-left border-b">Alamat</th>
                        <th class="py-2 px-3 text-left border-b">No. KWH</th>
                        <th class="py-2 px-3 text-left border-b">Daya / Tarif</th>
                        <th class="py-2 px-3 text-center border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $pelanggan)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3 border-b">{{ $loop->iteration + $pelanggans->firstItem() - 1 }}</td>
                            <td class="py-2 px-3 border-b whitespace-nowrap max-w-[180px] truncate">{{ $pelanggan->nama_pelanggan }}</td>
                            <td class="py-2 px-3 border-b">{{ $pelanggan->username }}</td>
                            <td class="py-2 px-3 border-b">{{ $pelanggan->alamat }}</td>
                            <td class="py-2 px-3 border-b">{{ $pelanggan->nomor_kwh }}</td>
                            <td class="py-2 px-3 border-b">
                                @if($pelanggan->tarifs)
                                    {{ number_format($pelanggan->tarifs->daya, 0, ',', '.') }} VA
                                    (Rp {{ number_format($pelanggan->tarifs->tarif_perkwh, 2, ',', '.') }}/kWh)
                                @else
                                    <span class="text-gray-400 italic">N/A</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 border-b text-center whitespace-nowrap">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('admin.pelanggans.edit', $pelanggan->id) }}"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.pelanggans.destroy', $pelanggan->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($pelanggans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $pelanggans->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
            </div>
        @endif
    </div>
</div>
@endsection
