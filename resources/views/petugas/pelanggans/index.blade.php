@extends('layouts.app')

{{-- Judul halaman --}}
@section('title', 'Daftar Pelanggan Petugas')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    {{-- Header halaman --}}
    <h1 class="text-3xl font-bold text-green-800 mb-6">Daftar Pelanggan</h1>

    {{-- Wrapper tabel agar bisa discroll horizontal --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-green-200 rounded-xl overflow-hidden shadow-sm">
            <thead class="bg-gradient-to-r from-green-500 to-green-600 text-white">
                <tr>
                    <th class="py-3 px-5 text-left font-semibold">ID</th>
                    <th class="py-3 px-5 text-left font-semibold">Nama Pelanggan</th>
                    <th class="py-3 px-5 text-left font-semibold">Username</th>
                    <th class="py-3 px-5 text-left font-semibold">Alamat</th>
                    <th class="py-3 px-5 text-left font-semibold">No. KWH</th>
                    <th class="py-3 px-5 text-left font-semibold">Daya / Tarif</th>
                    <th class="py-3 px-5 text-center font-semibold">Aksi</th>
                </tr>
            </thead>

            {{-- Isi data pelanggan --}}
            <tbody>
                @forelse($pelanggans as $pelanggan)
                <tr class="hover:bg-green-50 transition duration-200">
                    <td class="py-3 px-5 border-b border-green-100">{{ $pelanggan->id }}</td>
                    <td class="py-3 px-5 border-b border-green-100">{{ $pelanggan->nama_pelanggan }}</td>
                    <td class="py-3 px-5 border-b border-green-100">{{ $pelanggan->username }}</td>
                    <td class="py-3 px-5 border-b border-green-100">{{ $pelanggan->alamat }}</td>
                    <td class="py-3 px-5 border-b border-green-100">{{ $pelanggan->nomor_kwh }}</td>
                    <td class="py-3 px-5 border-b border-green-100">
                        @if($pelanggan->tarifs)
                            {{ number_format($pelanggan->tarifs->daya, 0, ',', '.') }} VA
                            <br>
                            <span class="text-sm text-gray-600 italic">Rp {{ number_format($pelanggan->tarifs->tarif_perkwh, 2, ',', '.') }}/kWh</span>
                        @else
                            <span class="text-gray-400 italic">N/A</span>
                        @endif
                    </td>
                    {{-- Tombol Edit dan Hapus --}}
                    <td class="py-3 px-5 border-b border-green-100 text-center">
                        @auth
                            @if (Auth::user()->role === 'petugas') {{-- atau 'petugas' jika ingin petugas juga bisa --}}
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('pelanggan.edit', $pelanggan->id) }}"
                                        class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-sm">Edit</a>
                                    <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Hapus</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-500 italic text-sm">Tidak ada aksi</span>
                            @endif
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-5 px-5 text-center text-gray-500 italic">
                        Belum ada data pelanggan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination jika ada --}}
        @if ($pelanggans->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $pelanggans->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
        </div>
        @endif
    </div>
</div>
@endsection
