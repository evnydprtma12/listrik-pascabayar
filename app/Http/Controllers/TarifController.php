<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TarifController extends Controller
{
    public function index()
    {
        try {
            $tarifs = Tarif::orderBy('daya', 'asc')->paginate(10);

            if (Auth::guard('web')->check()) {
                if (Auth::guard('web')->user()->level_id == 1) {
                    return view('admin.tarifs.index', compact('tarifs'));
                } elseif (Auth::guard('web')->user()->level_id == 2) {
                    return view('petugas.tarifs.index', compact('tarifs'));
                }
            }

            abort(403, 'Akses tidak diizinkan untuk melihat tarif.');
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data tarif: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data tarif.');
        }
    }

    public function create()
    {
        try {
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
                return view('admin.tarifs.create');
            }

            abort(403, 'Akses Dilarang. Hanya Admin yang dapat menambah tarif.');
        } catch (\Exception $e) {
            Log::error('Gagal membuka form create tarif: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka halaman tambah tarif.');
        }
    }

    public function store(Request $request)
    {
        try {
            if (!Auth::guard('web')->check() || Auth::guard('web')->user()->level_id != 1) {
                abort(403, 'Akses Dilarang. Hanya Admin yang dapat menyimpan tarif.');
            }

            $validated = $request->validate([
                'daya' => 'required|integer|min:100|unique:tarifs,daya',
                'tarif_perkwh' => 'required|numeric|min:0',
            ], [
                'daya.required' => 'Daya wajib diisi.',
                'daya.integer' => 'Daya harus berupa angka.',
                'daya.min' => 'Daya minimal :min.',
                'daya.unique' => 'Daya ini sudah terdaftar.',
                'tarif_perkwh.required' => 'Tarif per kWh wajib diisi.',
                'tarif_perkwh.numeric' => 'Tarif per kWh harus berupa angka.',
                'tarif_perkwh.min' => 'Tarif per kWh tidak boleh negatif.',
            ]);

            Tarif::create($validated);

            return redirect()->route('admin.tarifs.index')->with('success', 'Data tarif berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan tarif: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data tarif.')->withInput();
        }
    }

    public function edit(Tarif $tarif)
    {
        try {
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1) {
                return view('admin.tarifs.edit', compact('tarif'));
            }

            abort(403, 'Akses Dilarang. Hanya Admin yang dapat mengedit tarif.');
        } catch (\Exception $e) {
            Log::error('Gagal membuka form edit tarif: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka halaman edit tarif.');
        }
    }

    public function update(Request $request, Tarif $tarif)
    {
        try {
            if (!Auth::guard('web')->check() || Auth::guard('web')->user()->level_id != 1) {
                abort(403, 'Akses Dilarang. Hanya Admin yang dapat memperbarui tarif.');
            }

            $validated = $request->validate([
                'daya' => 'required|integer|min:100|unique:tarifs,daya,' . $tarif->id,
                'tarif_perkwh' => 'required|numeric|min:0',
            ], [
                'daya.required' => 'Daya wajib diisi.',
                'daya.integer' => 'Daya harus berupa angka.',
                'daya.min' => 'Daya minimal :min.',
                'daya.unique' => 'Daya ini sudah terdaftar.',
                'tarif_perkwh.required' => 'Tarif per kWh wajib diisi.',
                'tarif_perkwh.numeric' => 'Tarif per kWh harus berupa angka.',
                'tarif_perkwh.min' => 'Tarif per kWh tidak boleh negatif.',
            ]);

            $tarif->update($validated);

            return redirect()->route('admin.tarifs.index')->with('success', 'Data tarif berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui tarif: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data tarif.')->withInput();
        }
    }

    public function destroy(Tarif $tarif)
    {
        try {
            if (!Auth::guard('web')->check() || Auth::guard('web')->user()->level_id != 1) {
                abort(403, 'Akses Dilarang. Hanya Admin yang dapat menghapus tarif.');
            }

            $tarif->delete();
            return redirect()->route('admin.tarifs.index')->with('success', 'Data tarif berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus tarif: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data tarif.');
        }
    }
}
