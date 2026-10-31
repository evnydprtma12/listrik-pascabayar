@extends('layouts.app')

@section('title', 'Tagihan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Daftar Tagihan</h1>

    {{-- Form Filter dan Sort --}}
    <form method="GET" class="mb-4 flex gap-2 flex-wrap">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Bulan/Tahun"
            class="px-3 py-2 border border-gray-300 rounded bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        
        <select name="sort" class="px-3 py-2 border border-gray-300 rounded bg-gray-50 text-sm">
            <option value="tahun" {{ request('sort') == 'tahun' ? 'selected' : '' }}>Tahun</option>
             <option value="bulan" {{ request('sort') == 'bulan' ? 'selected' : '' }}>Bulan</option>
        </select>
        
        <select name="direction" class="px-3 py-2 border border-gray-300 rounded bg-gray-50 text-sm">
            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Terbaru</option>
            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Terlama</option>
        </select>
        
        <button type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
            Cari / Sort
        </button>
    </form>

    {{-- Tabel Tagihan --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-sm">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="py-2 px-4 border-b text-left">No</th>
                    <th class="py-2 px-4 border-b text-left">Periode</th>
                    <th class="py-2 px-4 border-b text-left">Jumlah Meter</th>
                    <th class="py-2 px-4 border-b text-left">Total Tagihan</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-center">Detail Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $tagihan)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border-b">{{ $tagihan->bulan }} {{ $tagihan->tahun }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($tagihan->jumlah_meter, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</td>
                        <td class="py-2 px-4 border-b">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $tagihan->status_tagihan == 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $tagihan->status_tagihan }}
                            </span>
                        </td>
                    <td class="py-2 px-4 border-b text-center">
    @if ($tagihan->status_tagihan == 'Sudah Dibayar')
        {{-- Button Lihat Pembayaran --}}
        <button
            onclick="showPaymentDetail(
                // Pastikan $tagihan->pembayaran tidak null sebelum mengakses propertinya.
                // optional($tagihan->pembayaran) akan mengembalikan null jika pembayaran null,
                // lalu optional kedua untuk tanggal_pembayaran jika itu juga null,
                // dan fallback ke '-'
                '{{ optional(optional($tagihan->pembayaran)->tanggal_pembayaran)->format('d M Y') ?? '-' }}',
                'Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}',
                'Rp {{ number_format(optional($tagihan->pembayaran)->biaya_admin ?? 0, 2, ',', '.') }}',
                'Rp {{ number_format(optional($tagihan->pembayaran)->total_bayar ?? 0, 2, ',', '.') }}',
                // Pastikan $tagihan->pembayaran tidak null, lalu $tagihan->pembayaran->user tidak null
                '{{ optional(optional($tagihan->pembayaran)->user)->nama_user ?? 'Dibayar Oleh Pelanggan' }}'
            )"
            class="bg-gradient-to-r from-green-600 via-green-500 to-green-400 hover:brightness-110 text-white text-sm py-1 px-3 rounded-md shadow-md transition duration-200">
            Lihat Pembayaran
        </button>
    @else
        <span class="text-gray-400 text-xs italic">Belum tersedia</span>
    @endif
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">Belum ada tagihan.</td>
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

{{-- Modal Detail Pembayaran --}}
<div id="paymentDetailModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-gradient-to-br from-green-100 via-white to-green-50 rounded-xl shadow-xl max-w-md w-full overflow-hidden animate-fade-in-down">
        
        {{-- Header Modal Gradasi Hijau --}}
        <div class="bg-gradient-to-r from-green-600 via-green-500 to-green-400 text-white px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Detail Pembayaran</h3>
        </div>

            <div class="p-6 text-gray-700 space-y-3 text-sm bg-green-50/60 backdrop-blur-sm rounded-b-xl">
            <div class="flex justify-between">
                <span class="font-medium">Tanggal Bayar:</span>
                <span id="modalTanggalBayar"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Total Tagihan:</span>
                <span id="modalTotalTagihan"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Biaya Admin:</span>
                <span id="modalBiayaAdmin"></span>
            </div>
            <div class="flex justify-between text-base font-semibold text-green-700">
                <span>Total Dibayar:</span>
                <span id="modalTotalDibayar"></span>
            </div>
            <div class="flex justify-between mt-3 border-t pt-3">
                <span class="font-medium">Dicatat Oleh:</span>
                <span id="modalDicatatOleh"></span>
            </div>
        </div>

        {{-- Tombol Tutup --}}
        <div class="px-6 pb-6">
            <button id="closeModalBottom"
                class="w-full py-2 bg-gradient-to-r from-green-600 via-green-500 to-green-400 hover:brightness-105 text-white rounded-md font-semibold transition duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Script Modal --}}
<script>
    const paymentDetailModal = document.getElementById('paymentDetailModal');
    const closeModalButton = document.getElementById('closeModalBottom');

    function showPaymentDetail(tanggalBayar, totalTagihan, biayaAdmin, totalDibayar, dicatatOleh) {
        document.getElementById('modalTanggalBayar').textContent = tanggalBayar;
        document.getElementById('modalTotalTagihan').textContent = totalTagihan;
        document.getElementById('modalBiayaAdmin').textContent = biayaAdmin;
        document.getElementById('modalTotalDibayar').textContent = totalDibayar;
        document.getElementById('modalDicatatOleh').textContent = dicatatOleh;
        paymentDetailModal.classList.remove('hidden');
    }

    closeModalButton.onclick = function () {
        paymentDetailModal.classList.add('hidden');
    }

    window.onclick = function (event) {
        if (event.target == paymentDetailModal) {
            paymentDetailModal.classList.add('hidden');
        }
    }
</script>
@endsection
