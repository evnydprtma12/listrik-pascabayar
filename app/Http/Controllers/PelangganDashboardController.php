<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\Penggunaan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Pelanggan;

class PelangganDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama pelanggan.
     */
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user(); // Ambil data pelanggan yang sedang login
        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan.');
        }

        // Ambil penggunaan listrik terakhir berdasarkan tahun dan bulan terbaru
        $lastPenggunaan = Penggunaan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        // Ambil tagihan terakhir yang belum dibayar
        $lastUnpaidTagihan = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->where('status_tagihan', 'Belum Dibayar')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        // Tampilkan halaman dashboard pelanggan
        return view('pelanggan.dashboard', compact('pelanggan', 'lastPenggunaan', 'lastUnpaidTagihan'));
    }

    /**
     * Menampilkan daftar tagihan pelanggan dengan fitur pencarian dan sorting.
     */
    public function tagihanSaya(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan.');
        }

        $query = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->with(['pembayaran.user'])
        ; // Ambil tagihan milik pelanggan

        // Fitur pencarian berdasarkan bulan, tahun, atau status tagihan
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('bulan', 'like', "%$q%")
                    ->orWhere('tahun', 'like', "%$q%")
                    ->orWhere('status_tagihan', 'like', "%$q%");
            });
        }

        // Sorting berdasarkan kolom tertentu (tahun atau created_at)
        $sort = $request->sort === 'tahun' ? 'tahun' : 'created_at';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $direction);

        // Paginate hasilnya dan tetap menyertakan parameter pencarian dan sorting
        $tagihans = $query->paginate(10)->appends($request->all());

        return view('pelanggan.tagihan_saya', compact('pelanggan', 'tagihans'));
    }

    /**
     * Menampilkan riwayat pembayaran pelanggan dengan fitur pencarian dan sorting.
     */
    public function riwayatPembayaran(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan.');
        }

        // Ambil data pembayaran pelanggan beserta relasi tagihan dan user
        $query = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->with(['tagihan', 'user']);

        // Fitur pencarian berdasarkan bulan/tahun atau total bayar
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->orWhereHas('tagihan', function($q2) use ($q) {
                    $q2->where('bulan', 'like', "%$q%")
                       ->orWhere('tahun', 'like', "%$q%");
                });

                if (is_numeric($q)) {
                    $sub->orWhere('total_bayar', '=', $q)
                        ->orWhereRaw('CAST(total_bayar AS CHAR) LIKE ?', ["%$q%"]);
                } else {
                    $sub->orWhereRaw('CAST(total_bayar AS CHAR) LIKE ?', ["%$q%"]);
                }
            });
        }

        // Sorting berdasarkan tanggal pembayaran atau total bayar
        $allowedSorts = ['tanggal_pembayaran', 'total_bayar'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : 'tanggal_pembayaran';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $direction);

        // Paginasi dan kembalikan view
        $riwayatPembayaran = $query->paginate(10)->appends($request->all());
     

        return view('pelanggan.riwayat_pembayaran', compact('pelanggan', 'riwayatPembayaran'));
    }

    /**
     * Menampilkan halaman profil pelanggan.
     */
    public function profilSaya()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan.');
        }

       $pelanggan = Pelanggan::with('tarifs')->find($pelanggan->id); // Ambil data relasi tarif untuk info daya pelanggan

        return view('pelanggan.profil_saya', compact('pelanggan'));
    }

    /**
     * Memperbarui profil pelanggan, termasuk password (jika diisi).
     */
    public function updateProfil(Request $request)
    {
        $authPelanggan = Auth::guard('pelanggan')->user();
        $pelanggan = \App\Models\Pelanggan::find($authPelanggan->id);

        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan.');
        }

        // Validasi input profil
        $rules = [
            'nama_pelanggan' => 'required|string|max:100',
            'username' => ['required', 'string', 'max:100', Rule::unique('pelanggan')->ignore($pelanggan->id)],
            'alamat' => 'required|string|max:200',
        ];

        // Jika password diisi, validasi password juga
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $validated = $request->validate($rules, [
            'nama_pelanggan.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh pelanggan lain.',
            'alamat.required' => 'Alamat wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Simpan data profil yang diperbarui
        $pelanggan->nama_pelanggan = $validated['nama_pelanggan'];
        $pelanggan->username = $validated['username'];
        $pelanggan->alamat = $validated['alamat'];

        if ($request->filled('password')) {
            $pelanggan->password = Hash::make($validated['password']);
        }

         $pelanggan->save();

        return redirect()->route('pelanggan.profil_saya')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Menampilkan riwayat penggunaan listrik pelanggan.
     */
    public function riwayatPenggunaan(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan.');
        }

        // Ambil riwayat penggunaan listrik pelanggan, urutkan berdasarkan tahun dan urutan bulan
        $riwayatPenggunaan = Penggunaan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tahun', 'desc')
            ->orderByRaw("
                CASE 
                    WHEN bulan = 'Januari' THEN 1
                    WHEN bulan = 'Februari' THEN 2
                    WHEN bulan = 'Maret' THEN 3
                    WHEN bulan = 'April' THEN 4
                    WHEN bulan = 'Mei' THEN 5
                    WHEN bulan = 'Juni' THEN 6
                    WHEN bulan = 'Juli' THEN 7
                    WHEN bulan = 'Agustus' THEN 8
                    WHEN bulan = 'September' THEN 9
                    WHEN bulan = 'Oktober' THEN 10
                    WHEN bulan = 'November' THEN 11
                    WHEN bulan = 'Desember' THEN 12
                    ELSE 13
                END desc
            ")
            ->paginate(10); // Batasi tampilan per halaman

        return view('pelanggan.riwayat_penggunaan', compact('riwayatPenggunaan', 'pelanggan'));
    }
}
