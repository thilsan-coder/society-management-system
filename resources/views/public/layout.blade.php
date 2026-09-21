<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100 selection:bg-teal-500 selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bonds of Friendship Association - STR (Official Portal)')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        society: {
                            teal: '#00a896',
                            tealDark: '#028090',
                            red: '#d90429',
                            orange: '#f77f00',
                            yellow: '#fcbf49',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        
        /* Custom subtle scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #090d16; }
        ::-webkit-scrollbar-thumb { background: #00a896; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #028090; }

        .hero-gradient {
            background: radial-gradient(circle at 50% 0%, rgba(0, 168, 150, 0.18) 0%, rgba(9, 13, 22, 0.95) 75%);
        }

        /* System-Wide Custom Select Styling */
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2300a896'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 1rem 1rem;
            padding-right: 2.5rem !important;
        }

        /* Subtle Logo Entrance Animation */
        @keyframes corporateFadeScale {
            0% { opacity: 0; transform: scale(0.96); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-corporate-entrance {
            animation: corporateFadeScale 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }" class="flex flex-col min-h-screen bg-slate-950 font-sans antialiased text-slate-200">

    <!-- Premium Header Navigation -->
    <header class="sticky top-0 z-50 bg-slate-950/90 backdrop-blur-md border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo & Title -->
            <a href="{{ route('public.home') }}" class="flex items-center space-x-3.5 group animate-corporate-entrance">
                <div class="p-1 rounded-full bg-white/10 group-hover:bg-teal-500/20 border border-teal-500/30 transition-all duration-300 shadow-lg shadow-teal-500/10">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-full bg-white p-0.5">
                </div>
                <div>
                    <span class="text-sm sm:text-base font-black tracking-tight text-white group-hover:text-teal-400 transition-colors uppercase">Bonds of Friendship</span>
                    <span class="block text-[10px] font-bold text-teal-400 uppercase tracking-widest -mt-0.5">Association - STR</span>
                </div>
            </a>

            <!-- Desktop Navigation Links (8 Pages) -->
            <nav class="hidden lg:flex items-center space-x-7 text-xs font-semibold uppercase tracking-wider">
                <a href="{{ route('public.home') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.home') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Home
                </a>
                <a href="{{ route('public.about') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.about') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    About Us
                </a>
                <a href="{{ route('public.committee') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.committee') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Committee
                </a>
                <a href="{{ route('public.members') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.members') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Members
                </a>
                <a href="{{ route('public.announcements') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.announcements') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Announcements
                </a>
                <a href="{{ route('public.activities') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.activities') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Activities
                </a>
                <a href="{{ route('public.media') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.media') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Gallery
                </a>
                <a href="{{ route('public.contact') }}" class="py-2 border-b-2 transition-all duration-200 {{ request()->routeIs('public.contact') ? 'border-teal-400 text-teal-400 font-bold' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-700' }}">
                    Contact Us
                </a>
            </nav>

            <!-- Action CTA Button -->
            <div class="hidden sm:flex items-center space-x-3">
                @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-extrabold uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all hover:scale-105">
                    Portal Dashboard ➔
                </a>
                @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-extrabold uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all hover:scale-105">
                    Member Login
                </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="lg:hidden p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-900 transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Alpine Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 pt-3 pb-6 space-y-2 text-xs font-semibold uppercase tracking-wider">
            <a href="{{ route('public.home') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Home</a>
            <a href="{{ route('public.about') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">About Us</a>
            <a href="{{ route('public.committee') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Committee</a>
            <a href="{{ route('public.members') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Members Directory</a>
            <a href="{{ route('public.announcements') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Announcements</a>
            <a href="{{ route('public.activities') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Activities & Events</a>
            <a href="{{ route('public.media') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Media Gallery</a>
            <a href="{{ route('public.contact') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-200 hover:text-teal-400">Contact Us</a>
            <div class="pt-3 border-t border-slate-800">
                @auth
                <a href="{{ route('dashboard') }}" class="block text-center px-4 py-2.5 bg-teal-500 text-slate-950 rounded-lg font-bold">Dashboard ➔</a>
                @else
                <a href="{{ route('login') }}" class="block text-center px-4 py-2.5 bg-teal-500 text-slate-950 rounded-lg font-bold">Member Login</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Multi-Column Premium Footer -->
    <footer class="bg-slate-950 border-t border-slate-800/80 text-slate-400 text-xs pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <!-- Col 1: Brand -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 w-10 object-contain rounded-full bg-white p-0.5">
                    <div>
                        <span class="font-black text-white text-sm uppercase">Bonds of Friendship</span>
                        <span class="block text-[10px] text-teal-400 uppercase font-bold tracking-widest">Association - STR</span>
                    </div>
                </div>
                <p class="text-slate-400 leading-relaxed text-xs">
                    Official association portal committed to fellowship, community empowerment, structured organization, and social development.
                </p>
            </div>

            <!-- Col 2: Sitemap Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Quick Navigation</h4>
                <ul class="space-y-2 font-medium">
                    <li><a href="{{ route('public.home') }}" class="hover:text-teal-400 transition-colors">Home Page</a></li>
                    <li><a href="{{ route('public.about') }}" class="hover:text-teal-400 transition-colors">About Association</a></li>
                    <li><a href="{{ route('public.committee') }}" class="hover:text-teal-400 transition-colors">Executive Committee</a></li>
                    <li><a href="{{ route('public.members') }}" class="hover:text-teal-400 transition-colors">Public Members Directory</a></li>
                </ul>
            </div>

            <!-- Col 3: Public Updates -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Public Portals</h4>
                <ul class="space-y-2 font-medium">
                    <li><a href="{{ route('public.announcements') }}" class="hover:text-teal-400 transition-colors">Announcements & News</a></li>
                    <li><a href="{{ route('public.activities') }}" class="hover:text-teal-400 transition-colors">Activities & Events</a></li>
                    <li><a href="{{ route('public.media') }}" class="hover:text-teal-400 transition-colors">Media Gallery</a></li>
                    <li><a href="{{ route('public.contact') }}" class="hover:text-teal-400 transition-colors">Contact Information</a></li>
                </ul>
            </div>

            <!-- Col 4: Official Contact & Access -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Member Access</h4>
                <p class="text-slate-400 text-xs">Authorized members can sign in to access their private account records.</p>
                <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-slate-900 hover:bg-slate-800 text-teal-400 border border-teal-500/30 rounded-lg text-xs font-bold transition-all">
                    Sign In to Portal ➔
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between text-slate-500 text-[11px] gap-4">
            <p>&copy; {{ date('Y') }} Bonds of Friendship Association - STR. All rights reserved.</p>
            <p>Official Public Association Website &bull; Database Verified Published Records</p>
        </div>
    </footer>

</body>
</html>
