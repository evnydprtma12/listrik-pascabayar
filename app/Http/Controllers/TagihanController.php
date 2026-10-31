<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Penggunaan;
use App\Models\Tarif;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
        {
            $query = Tagihan::with('pelanggan.tarifs');

            if (request('sort') && request('direction')) {
                if (request('sort') == 'tahun') {
                    $query->orderBy('tahun', request('direction'));
                } elseif (request('sort') == 'bulan') {
                    $query->orderByRaw("FIELD(bulan, 
                        'Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember') " . (request('direction') == 'desc' ? 'DESC' : 'ASC'));
                }
            } else {
                // Default: sort by tahun desc, lalu bulan desc
                $query->orderBy('tahun', 'desc')
                    ->orderByRaw("FIELD(bulan, 
                        'Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember') DESC");
            }

            $tagihans = $query->paginate(10);

            return view('tagihan.index', compact('tagihans'));
        }

    /**
     * Tampilkan halaman untuk membuat tagihan baru dari penggunaan
     * yang belum memiliki tagihan.
     */
    public function createFromPenggunaan()
    {
        $penggunaansBelumDitagih = Penggunaan::doesntHave('tagihan')
            ->with('pelanggan.tarifs')
            ->orderBy('tahun', 'asc')
            ->orderByRaw("FIELD(bulan, 
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember') ASC")

            ->get();

        return view('tagihan.create_from_penggunaan', compact('penggunaansBelumDitagih'));
    }

    /**
     * Proses pembuatan tagihan dari penggunaan yang dipilih.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'penggunaan_ids' => 'required|array',
            'penggunaan_ids.*' => 'exists:penggunaan,id',
        ], [
            'penggunaan_ids.required' => 'Pilih setidaknya satu data penggunaan untuk membuat tagihan.',
            'penggunaan_ids.*.exists' => 'Salah satu data penggunaan tidak valid.',
        ]);

        $countGenerated = 0;
        DB::beginTransaction();
        try {
            foreach ($validated['penggunaan_ids'] as $penggunaanId) {
                $penggunaan = Penggunaan::with('pelanggan.tarifs')->find($penggunaanId);

                if ($penggunaan && !$penggunaan->tagihan) {
                    $tarifPerKwh = optional($penggunaan->pelanggan->tarifs)->tarif_perkwh;

                    if ($tarifPerKwh === null) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal membuat tagihan: Tarif untuk pelanggan ' . optional($penggunaan->pelanggan)->nama_pelanggan . ' (No. KWH: ' . optional($penggunaan->pelanggan)->nomor_kwh . ') tidak ditemukan. Harap pastikan tarif pelanggan sudah diatur.');
                    }

                    Tagihan::create([
                        'penggunaan_id' => $penggunaan->id,
                        'pelanggan_id' => $penggunaan->pelanggan_id,
                        'jumlah_meter' => $penggunaan->meter_akhir - $penggunaan->meter_awal,
                        'bulan' => $penggunaan->bulan,
                        'tahun' => $penggunaan->tahun,
                        'status_tagihan' => 'Belum Dibayar',
                    ]);
                    $countGenerated++;
                }
            }
            DB::commit();
            return redirect()->route(Auth::guard('web')->user()->level_id == 1 ? 'admin.tagihans.index' : 'petugas.tagihans.index')->with('success', $countGenerated . ' tagihan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat tagihan: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Tagihan $tagihan)
    {
        return redirect()->route(Auth::guard('web')->user()->level_id == 1 ? 'admin.tagihans.index' : 'petugas.tagihans.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        $tagihan->load('pelanggan.tarifs', 'penggunaan');
        return view('tagihan.edit', compact('tagihan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'status_tagihan' => 'required|in:Belum Dibayar,Sudah Dibayar',
        ], [
            'status_tagihan.required' => 'Status tagihan wajib diisi.',
            'status_tagihan.in' => 'Status tagihan tidak valid.',
        ]);

        $tagihan->update($validated);

        return redirect()->route(Auth::guard('web')->user()->level_id == 1 ? 'admin.tagihans.index' : 'petugas.tagihans.index')->with('success', 'Status tagihan berhasil diperbarui menjadi ' . $tagihan->status_tagihan . '!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        try {
            $tagihan->delete();
            return redirect()->route(Auth::guard('web')->user()->level_id == 1 ? 'admin.tagihans.index' : 'petugas.tagihans.index')->with('success', 'Tagihan berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route(Auth::guard('web')->user()->level_id == 1 ? 'admin.tagihans.index' : 'petugas.tagihans.index')->with('error', 'Tidak dapat menghapus tagihan ini karena sudah memiliki data pembayaran terkait.');
        }
    }
}