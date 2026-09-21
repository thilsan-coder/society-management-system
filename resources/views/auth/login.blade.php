<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bonds of Friendship Association (STR)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full flex items-center justify-center p-4 font-sans antialiased text-slate-800 bg-slate-900">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
        <!-- Brand Header with Logo -->
        <div class="bg-gradient-to-r from-teal-700 via-teal-600 to-teal-800 p-8 text-center text-white relative">
            <div class="inline-block p-2 bg-white rounded-full shadow-lg mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">
            </div>
            <h2 class="text-xl font-extrabold tracking-tight">Bonds of Friendship</h2>
            <p class="text-xs text-teal-100 font-semibold tracking-wide uppercase mt-1">Association - STR</p>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="p-8 space-y-6">
            @csrf

            @if($errors->any())
            <div class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded">
                {{ $errors->first() }}
            </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full h-11 px-4 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition-all"
                    placeholder="name@society.com">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full h-11 px-4 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition-all"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 h-4 w-4">
                    <span class="ml-2">Remember me</span>
                </label>
            </div>

            <button type="submit" 
                class="w-full h-11 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all text-sm tracking-wide">
                Sign In to Account
            </button>
        </form>

        <div class="px-8 py-4 bg-slate-50 border-t border-slate-200 text-center text-xs text-slate-500">
            Official Central Society Management System &bull; Multi-User Authorized Access
        </div>
    </div>

</body>
</html>
