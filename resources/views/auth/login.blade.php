<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-300 via-blue-200/50 to-blue-200/50">

    <!-- BACKGROUND -->
    <div class="absolute w-[300px] h-[300px] bg-blue-300/20 rounded-full blur-3xl top-10 left-10"></div>
    <div class="absolute w-[250px] h-[250px] bg-blue-400/20 rounded-full blur-3xl bottom-10 right-10"></div>

    <!-- CARD -->
    <div class="relative w-full max-w-md bg-white/70 backdrop-blur-xl p-10 rounded-2xl shadow-xl border border-blue-100">

        <!-- TITLE -->
        <div class="text-center mb-6">
            <div class="text-4xl text-blue-600 mb-2">👤</div>
            <h2 class="text-2xl font-semibold text-blue-900">EduBook</h2>
            <p class="text-blue-400 text-sm">Silakan login untuk melanjutkan</p>
        </div>

        <!-- SESSION STATUS -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-center text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="text-blue-800">Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    class="w-full mt-1 mb-3 px-4 py-3 rounded-full border border-blue-200 bg-white/80 focus:ring-2 focus:ring-blue-400 outline-none"
                    required autofocus>

                @error('email')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mt-2">
                <label class="text-blue-800">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 mb-3 px-4 py-3 rounded-full border border-blue-200 bg-white/80 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>

                @error('password')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <!-- REMEMBER -->
            <div class="flex items-center mb-4">
                <input type="checkbox" name="remember" class="mr-2">
                <span class="text-blue-700 text-sm">Remember me</span>
            </div>

            <!-- FORGOT -->
            @if (Route::has('password.request'))
                <div class="text-right mb-3">
                    <a href="{{ route('password.request') }}"
                        class="text-blue-500 text-sm hover:underline">
                        Forgot Password?
                    </a>
                </div>
            @endif

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 rounded-full text-lg transition shadow-md">
                Login
            </button>

        </form>

    </div>

</body>
</html>