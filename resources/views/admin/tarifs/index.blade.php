@extends('layouts.app')

@section('title', 'Manajemen Tarif Listrik Admin')

@section('content')
<div class="bg-gradient-to-tr from-green-100 via-green-50 to-green-200 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white w-full p-6 rounded-xl shadow-md overflow-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Daftar Tarif Listrik</h1>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tombol Tambah Tarif --}}
        @if(Auth::guard('web')->user()->level_id == 1)
        <div class="mb-4 text-right">
            <a href="{{ route('admin.tarifs.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition">
                + Tambah Tarif Baru
            </a>
        </div>
        @endif

        {{-- Tabel Tarif --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full bg-white text-sm text-gray-700">
                <thead class="bg-green-100 text-green-800">
                    <tr>
                        <th class="py-3 px-4 text-left font-semibold">No</th>
                        <th class="py-3 px-4 text-left font-semibold">Daya (VA)</th>
                        <th class="py-3 px-4 text-left font-semibold">Tarif per kWh (Rp)</th>
                        <th class="py-3 px-4 text-left font-semibold">Terakhir Diperbarui</th>
                        <th class="py-3 px-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarifs as $tarif)
                    <tr class="hover:bg-green-50 transition">
                        <td class="py-3 px-4 border-t">{{ $loop->iteration + $tarifs->firstItem() - 1 }}</td>
                        <td class="py-3 px-4 border-t">{{ number_format($tarif->daya, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 border-t">Rp {{ number_format($tarif->tarif_perkwh, 2, ',', '.') }}</td>
                        <td class="py-3 px-4 border-t">{{ $tarif->updated_at->format('d M Y H:i') }}</td>
                        <td class="py-3 px-4 border-t text-center">
                            @if(Auth::guard('web')->user()->level_id == 1)
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.tarifs.edit', $tarif->id) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white text-sm py-1 px-3 rounded-md transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.tarifs.destroy', $tarif->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus tarif ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded-md transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-gray-500 text-sm italic">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 px-4 text-center text-gray-500 italic">Belum ada data tarif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tarifs->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $tarifs->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
        </div>
        @endif
    </div>
</div>
@endsection
