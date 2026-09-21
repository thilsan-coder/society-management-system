<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bonds of Friendship Association (STR)</title>
    
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
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes corporateFadeScale {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-corporate-entrance {
            animation: corporateFadeScale 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans antialiased text-slate-100 bg-slate-950">

    <div class="w-full max-w-md bg-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-800 animate-corporate-entrance">
        <!-- Brand Header with Logo & Entrance Animation -->
        <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950/60 p-8 text-center text-white border-b border-slate-800 space-y-3 relative">
            <div class="inline-block p-2 rounded-full bg-slate-950 border border-teal-500/30 shadow-xl shadow-teal-500/10">
                <img src="{{ asset('images/logo.png') }}" alt="Society Emblem" class="h-16 w-16 object-contain rounded-full bg-white p-1">
            </div>
            <div>
                <h2 class="text-xl font-black uppercase tracking-tight text-white">Bonds of Friendship</h2>
                <p class="text-xs text-teal-400 font-extrabold tracking-widest uppercase mt-0.5">Association - STR</p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 text-[10px] font-bold uppercase tracking-wider">
                Official Governance Portal
            </span>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="p-8 space-y-5">
            @csrf

            @if($errors->any())
            <div class="p-3.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs rounded-xl font-medium">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full h-11 px-4 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none transition-all placeholder-slate-500"
                    placeholder="name@society.com">
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full h-11 px-4 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none transition-all placeholder-slate-500"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-800 bg-slate-950 text-teal-500 focus:ring-teal-500 h-4 w-4">
                    <span class="ml-2 text-xs font-medium">Keep me signed in</span>
                </label>
            </div>

            <button type="submit" 
                class="w-full h-11 bg-teal-500 hover:bg-teal-400 text-slate-950 font-black rounded-xl shadow-lg shadow-teal-500/20 hover:scale-[1.02] transition-all text-xs uppercase tracking-wider">
                Sign In to Account ➔
            </button>
        </form>

        <div class="px-8 py-4 bg-slate-950 border-t border-slate-800/80 text-center text-[11px] text-slate-500">
            Official Central Society Management System &bull; Authorized Personnel Only
        </div>
    </div>

</body>
</html>
