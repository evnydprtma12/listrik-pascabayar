<?php

namespace App\Http\Controllers;

use App\Models\Penggunaan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenggunaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penggunaans = Penggunaan::with('pelanggan')
                            ->orderBy('tahun', 'desc')
                            ->orderByRaw("FIELD(bulan, 
                                'Januari','Februari','Maret','April','Mei','Juni',
                                'Juli','Agustus','September','Oktober','November','Desember')")
                            ->paginate(10);

        return view('penggunaan.index', compact('penggunaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return view('penggunaan.create', compact('pelanggans', 'bulanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'bulan' => 'required|string|max:20',
            'tahun' => 'required|integer|digits:4',
            'meter_awal' => 'required|integer|min:0',
            'meter_akhir' => 'required|integer|min:0|gt:meter_awal',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'pelanggan_id.exists' => 'Pelanggan tidak valid.',
            'bulan.required' => 'Bulan wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.digits' => 'Tahun harus 4 digit angka.',
            'meter_awal.required' => 'Meter awal wajib diisi.',
            'meter_awal.integer' => 'Meter awal harus angka.',
            'meter_awal.min' => 'Meter awal tidak boleh negatif.',
            'meter_akhir.required' => 'Meter akhir wajib diisi.',
            'meter_akhir.integer' => 'Meter akhir harus angka.',
            'meter_akhir.min' => 'Meter akhir tidak boleh negatif.',
            'meter_akhir.gt' => 'Meter akhir harus lebih besar dari meter awal.',
        ]);

        $existingPenggunaan = Penggunaan::where('pelanggan_id', $validated['pelanggan_id'])
                                        ->where('bulan', $validated['bulan'])
                                        ->where('tahun', $validated['tahun'])
                                        ->first();

        if ($existingPenggunaan) {
            return back()->withErrors([
                'bulan' => 'Data penggunaan untuk pelanggan ini pada bulan dan tahun tersebut sudah ada.'
            ])->withInput();
        }

        Penggunaan::create($validated);

        if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
            return redirect()->route('admin.penggunaans.index')->with('success', 'Data penggunaan berhasil ditambahkan!');
        }
        return redirect()->route('petugas.penggunaans.index')->with('success', 'Data penggunaan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penggunaan $penggunaan)
    {
        return redirect()->route('admin.penggunaans.index');
    }

    /**
     * Show the form for editing the specified resource.
     */

    // urutan sesuai bulan
    public function edit(Penggunaan $penggunaan)
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return view('penggunaan.edit', compact('penggunaan', 'pelanggans', 'bulanList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penggunaan $penggunaan)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'bulan' => 'required|string|max:20',
            'tahun' => 'required|integer|digits:4',
            'meter_awal' => 'required|integer|min:0',
            'meter_akhir' => 'required|integer|min:0|gt:meter_awal',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'pelanggan_id.exists' => 'Pelanggan tidak valid.',
            'bulan.required' => 'Bulan wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.digits' => 'Tahun harus 4 digit angka.',
            'meter_awal.required' => 'Meter awal wajib diisi.',
            'meter_awal.integer' => 'Meter awal harus angka.',
            'meter_awal.min' => 'Meter awal tidak boleh negatif.',
            'meter_akhir.required' => 'Meter akhir wajib diisi.',
            'meter_akhir.integer' => 'Meter akhir harus angka.',
            'meter_akhir.min' => 'Meter akhir tidak boleh negatif.',
            'meter_akhir.gt' => 'Meter akhir harus lebih besar dari meter awal.',
        ]);

        $existingPenggunaan = Penggunaan::where('pelanggan_id', $validated['pelanggan_id'])
                                        ->where('bulan', $validated['bulan'])
                                        ->where('tahun', $validated['tahun'])
                                        ->where('id', '!=', $penggunaan->id)
                                        ->first();

        if ($existingPenggunaan) {
            return back()->withErrors([
                'bulan' => 'Data penggunaan untuk pelanggan ini pada bulan dan tahun tersebut sudah ada.'
            ])->withInput();
        }

        $penggunaan->update($validated);

        if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
            return redirect()->route('admin.penggunaans.index')->with('success', 'Data penggunaan berhasil diperbarui!');
        }
        return redirect()->route('petugas.penggunaans.index')->with('success', 'Data penggunaan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penggunaan $penggunaan)
    {
        try {
            $penggunaan->delete();
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
                return redirect()->route('admin.penggunaans.index')->with('success', 'Data penggunaan berhasil dihapus!');
            }
            return redirect()->route('petugas.penggunaans.index')->with('success', 'Data penggunaan berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
                return redirect()->route('admin.penggunaans.index')->with('error', 'Tidak dapat menghapus data penggunaan ini karena memiliki tagihan terkait.');
            }
            return redirect()->route('petugas.penggunaans.index')->with('error', 'Tidak dapat menghapus data penggunaan ini karena memiliki tagihan terkait.');
        }
    }
}
