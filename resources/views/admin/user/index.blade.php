@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="bg-gradient-to-tr from-green-100 via-green-50 to-green-200 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white w-full p-6 rounded-xl shadow-md overflow-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Daftar User</h1>

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

        {{-- Tombol Tambah --}}
        <div class="mb-4 text-right">
            <a href="{{ route('admin.users.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                + Tambah User Baru
            </a>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-green-100 text-green-700">
                    <tr>
                        <th class="py-2 px-3 text-left border-b">No</th>
                        <th class="py-2 px-3 text-left border-b">Nama User</th>
                        <th class="py-2 px-3 text-left border-b">Username</th>
                        <th class="py-2 px-3 text-left border-b">Level</th>
                        <th class="py-2 px-3 text-center border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3 border-b">{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                            <td class="py-2 px-3 border-b">{{ $user->nama_user }}</td>
                            <td class="py-2 px-3 border-b">{{ $user->username }}</td>
                            <td class="py-2 px-3 border-b">{{ $user->level->nama_level ?? 'N/A' }}</td>
                            <td class="py-2 px-3 border-b text-center whitespace-nowrap">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium py-1 px-3 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');" class="inline-block">
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
                            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $users->onEachSide(1)->links('vendor.pagination.tailwind-hijau') }}
            </div>
        @endif
    </div>
</div>
@endsection
