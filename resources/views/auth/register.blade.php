<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-300 via-blue-200/50 to-blue-200/50">

    <!-- BACKGROUND -->
    <div class="absolute w-[250px] h-[250px] bg-blue-300/20 rounded-full blur-3xl top-10 left-10"></div>
    <div class="absolute w-[200px] h-[200px] bg-blue-400/20 rounded-full blur-3xl bottom-10 right-10"></div>

    <!-- CARD -->
    <div class="relative w-full max-w-sm bg-white/70 backdrop-blur-xl p-6 rounded-2xl shadow-xl border border-blue-100">

        <!-- TITLE -->
        <div class="text-center mb-4">
            <div class="text-3xl text-blue-600 mb-1">📝</div>
            <h2 class="text-xl font-semibold text-blue-900">EduBook</h2>
            <p class="text-blue-400 text-xs">Silakan daftar untuk membuat akun</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NAME -->
            <div>
                <label class="text-blue-800 text-sm">Name</label>
                <input type="text" name="name"
                    value="{{ old('name') }}"
                    class="w-full mt-1 mb-2 px-3 py-2 text-sm rounded-full border border-blue-200 bg-white/80 focus:ring-2 focus:ring-blue-400 outline-none"
                    required autofocus>

                @error('name')
                    <div class="text-red-500 text-xs">{{ $message }}</div>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="text-blue-800 text-sm">Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    class="w-full mt-1 mb-2 px-3 py-2 text-sm rounded-full border border-blue-200 bg-white/80 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                @error('email')
                    <div class="text-red-500 text-xs">{{ $message }}</div>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-blue-800 text-sm">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 mb-2 px-3 py-2 text-sm rounded-full border border-blue-200 bg-white/80 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                @error('password')
                    <div class="text-red-500 text-xs">{{ $message }}</div>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->
            <div>
                <label class="text-blue-800 text-sm">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full mt-1 mb-3 px-3 py-2 text-sm rounded-full border border-blue-200 bg-white/80 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                @error('password_confirmation')
                    <div class="text-red-500 text-xs">{{ $message }}</div>
                @enderror
            </div>

            <!-- LOGIN LINK -->
            <div class="text-right mb-2">
                <a href="{{ route('login') }}"
                    class="text-blue-500 text-xs hover:underline">
                    Sudah punya akun?
                </a>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2 rounded-full text-sm transition shadow-md">
                Register
            </button>

        </form>

    </div>

</body>
</html>