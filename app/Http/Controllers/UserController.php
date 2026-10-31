<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level; // Import Model Level
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Support\Facades\Auth; // Untuk otorisasi

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya Admin yang bisa mengakses fitur ini (middleware sudah menghandle)
         
        // Eager load relasi 'level'
        $users = User::with('level')->orderBy('nama_user')->paginate(10);
      
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya Admin yang bisa mengakses fitur ini
        $levels = Level::all(); // Ambil semua data level
        return view('admin.user.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_user' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username', // Unique di tabel 'users'
            'password' => 'required|string|min:6',
            'level_id' => 'required|exists:level,id', // Pastikan level_id ada di tabel 'level'
        ], [
            'nama_user.required' => 'Nama user wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'level_id.required' => 'Level wajib dipilih.',
            'level_id.exists' => 'Level tidak valid.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Biasanya tidak diperlukan view show terpisah
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Hanya Admin yang bisa mengakses fitur ini
        $levels = Level::all();
        return view('admin.user.edit', compact('user', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'nama_user' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id, // Unique kecuali untuk dirinya sendiri
            'level_id' => 'required|exists:level,id',
        ];

        // Password hanya required jika diisi (untuk update)
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $validated = $request->validate($rules, [
            'nama_user.required' => 'Nama user wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
            'level_id.required' => 'Level wajib dipilih.',
            'level_id.exists' => 'Level tidak valid.',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // Jangan update password jika kosong
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Mencegah Admin menghapus dirinya sendiri
        if (Auth::guard('web')->id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Mencegah Admin menghapus Admin lain jika hanya ada satu Admin tersisa (opsional, untuk keamanan ekstra)
        $adminCount = User::where('level_id', 1)->count();
        if ($user->level_id == 1 && $adminCount <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir. Setidaknya harus ada satu akun admin.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error jika ada foreign key constraint (misal: user mencatat pembayaran)
            return back()->with('error', 'Tidak dapat menghapus user ini karena memiliki data terkait (misalnya, pembayaran yang dicatat).');
        }
    }
}