<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bonds of Friendship Association - STR (Official Portal)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen font-sans antialiased text-slate-800 bg-slate-50">

    <!-- Public Navigation Bar -->
    <header class="bg-slate-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('public.home') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-full bg-white p-0.5">
                <div>
                    <span class="text-sm font-extrabold uppercase tracking-wide text-teal-400">Bonds of Friendship</span>
                    <span class="block text-[10px] text-slate-400 uppercase tracking-widest">Association - STR</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center space-x-6 text-xs font-semibold uppercase tracking-wider">
                <a href="{{ route('public.home') }}" class="hover:text-teal-400 transition-colors">Home</a>
                <a href="{{ route('public.about') }}" class="hover:text-teal-400 transition-colors">About</a>
                <a href="{{ route('public.committee') }}" class="hover:text-teal-400 transition-colors">Executive Committee</a>
                <a href="{{ route('public.media') }}" class="hover:text-teal-400 transition-colors">Posters & Activities</a>
                <a href="{{ route('public.reports') }}" class="hover:text-teal-400 transition-colors">Public Reports</a>
                <a href="{{ route('public.contact') }}" class="hover:text-teal-400 transition-colors">Contact Us</a>
            </nav>

            <div>
                @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold shadow transition-all">
                    Go to Portal Dashboard ➔
                </a>
                @else
                <a href="{{ route('login') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold shadow transition-all">
                    Member Login
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Public Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="bg-slate-950 text-slate-400 text-xs py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 text-center space-y-2">
            <div class="flex items-center justify-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" class="h-6 w-6 object-contain">
                <span class="font-bold text-white uppercase text-sm">Bonds of Friendship Association - STR</span>
            </div>
            <p>&copy; {{ date('Y') }} Bonds of Friendship Association - STR. All rights reserved.</p>
            <p class="text-[10px] text-slate-500">Official Society Public Website Portal &bull; Verified Published Information Only</p>
        </div>
    </footer>

</body>
</html>
