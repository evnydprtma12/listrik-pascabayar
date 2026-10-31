@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('title', 'Riwayat Penggunaan Listrik') {{-- Judul halaman --}}

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-green-800 mb-6">Riwayat Penggunaan Listrik</h1>

    {{-- Wrapper tabel untuk membuat tampilan responsif dan diberi shadow --}}
    <div class="overflow-x-auto rounded-md shadow">
        <table class="min-w-full bg-white border border-gray-200">
            {{-- Header tabel --}}
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="py-2 px-4 border-b text-left">No</th>
                    <th class="py-2 px-4 border-b text-left">Bulan/Tahun</th>
                    <th class="py-2 px-4 border-b text-left">Meter Awal</th>
                    <th class="py-2 px-4 border-b text-left">Meter Akhir</th>
                    <th class="py-2 px-4 border-b text-left">Jumlah Meter</th>
                    <th class="py-2 px-4 border-b text-left">Dicatat Pada</th>
                </tr>
            </thead>

            {{-- Isi tabel --}}
            <tbody>
                {{-- Perulangan data penggunaan listrik --}}
                @forelse($riwayatPenggunaan as $penggunaan)
                <tr class="hover:bg-green-50 transition">
                    {{-- Nomor urut (otomatis dari loop) --}}
                    <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>

                    {{-- Periode penggunaan (bulan dan tahun) --}}
                    <td class="py-2 px-4 border-b">{{ $penggunaan->bulan }} {{ $penggunaan->tahun }}</td>

                    {{-- Meter awal penggunaan, diformat ribuan --}}
                    <td class="py-2 px-4 border-b">{{ number_format($penggunaan->meter_awal, 0, ',', '.') }}</td>

                    {{-- Meter akhir penggunaan --}}
                    <td class="py-2 px-4 border-b">{{ number_format($penggunaan->meter_akhir, 0, ',', '.') }}</td>

                    {{-- Selisih meter sebagai jumlah KWH --}}
                    <td class="py-2 px-4 border-b text-green-700 font-semibold">
                        {{ number_format($penggunaan->jumlah_meter, 0, ',', '.') }} KWH
                    </td>

                    {{-- Tanggal dan jam terakhir data ini diubah (biasanya saat dicatat) --}}
                    <td class="py-2 px-4 border-b">{{ $penggunaan->updated_at->format('d M Y H:i') }}</td>
                </tr>

                {{-- Jika tidak ada data sama sekali --}}
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-4 text-center text-gray-500 italic">
                        Belum ada riwayat penggunaan listrik.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination jika data lebih dari satu halaman --}}
        @if ($riwayatPenggunaan->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $riwayatPenggunaan->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
            </div>
        @endif
    </div>
</div>
@endsection
