<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Pembayaran Listrik</title>
    
    {{-- Mengimpor file CSS utama dengan Vite --}}
    @vite('resources/css/app.css')

    <style>
        body {
            background: linear-gradient(135deg, #d1fae5, #e5e7eb); /* gradasi hijau ke abu */
            background-attachment: fixed;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    {{-- Kontainer utama card login --}}
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm border-t-4 border-green-500">
        
        {{-- Logo aplikasi --}}
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-20 h-20 object-contain rounded-full shadow">
        </div>

        {{-- Judul halaman login --}}
        <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Login Aplikasi Listrik</h2>

        {{-- Tampilkan error khusus dari validasi username jika ada --}}
        @if ($errors->has('username') && !$errors->has('username.required'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ $errors->first('username') }}</span>
            </div>
        @endif

        {{-- Form login --}}
        <form action="{{ route('login') }}" method="POST">
            @csrf {{-- Token keamanan Laravel wajib untuk semua form POST --}}

            {{-- Input username --}}
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                <input type="text" id="username" name="username"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 @error('username') border-red-500 @enderror"
                    value="{{ old('username') }}" required autofocus>
                @error('username')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input password --}}
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror"
                    required>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol submit login --}}
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full transition duration-200">
                Login
            </button>
        </form>

        {{-- Catatan di bawah form --}}
        <p class="text-center text-sm text-gray-600 mt-4">
            Masukkan username dan password Anda. Sistem akan mendeteksi peran secara otomatis.
        </p>
        

    </div>

</body>
</html>
