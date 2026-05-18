<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>matahariWrite - Real-Time Story Writing Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
    </head>
    <body class="bg-zinc-950 text-zinc-100 min-h-screen selection:bg-amber-500 selection:text-zinc-950">
        <!-- Background Gradient Glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] pointer-events-none opacity-30 blur-[120px] bg-gradient-to-b from-amber-500/20 via-orange-600/10 to-transparent"></div>

        <div class="max-w-6xl mx-auto px-6 py-8 relative z-10 flex flex-col min-h-screen justify-between">
            <!-- Navigation -->
            <header class="flex items-center justify-between border-b border-zinc-800/80 pb-6">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-amber-400 via-orange-500 to-rose-500 bg-clip-text text-transparent">
                        matahariWrite
                    </span>
                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full">Beta</span>
                </a>

                <nav class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-semibold text-zinc-200 hover:text-white border border-zinc-800 hover:border-zinc-700 bg-zinc-900/50 hover:bg-zinc-900 rounded-xl transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-zinc-400 hover:text-zinc-200 transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/10">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="py-16 md:py-24 flex-grow">
                <div class="grid md:grid-cols-12 gap-12 items-center">
                    <div class="md:col-span-7 space-y-6">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight leading-tight sm:leading-none">
                            Tulis Cerita Bersama <br class="hidden sm:block">
                            Secara <span class="bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Real-Time</span>
                        </h1>
                        <p class="text-zinc-400 text-lg leading-relaxed max-w-xl">
                            Platform kolaboratif menulis cerita. Undang teman, pilih genre favorit, dan buat karya tulis luar biasa secara bersamaan tanpa refresh halaman.
                        </p>
                        <div class="pt-4 flex flex-wrap gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="px-6 py-3 font-semibold text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/20">
                                    Buka Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="px-6 py-3 font-semibold text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/20">
                                    Mulai Menulis Gratis
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="md:col-span-5 bg-zinc-900/50 border border-zinc-800/80 rounded-3xl p-6 shadow-2xl backdrop-blur-sm relative">
                        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl"></div>
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2 border-b border-zinc-800 pb-4">
                            <span>🔥 Cerita Terbaru</span>
                        </h2>

                        @if($latestStories->isEmpty())
                            <div class="text-center py-8 text-zinc-500 space-y-3">
                                <svg class="w-12 h-12 mx-auto stroke-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <p class="text-sm">Belum ada cerita yang dibuat. Jadilah yang pertama!</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($latestStories as $story)
                                    <div class="group border border-zinc-850 hover:border-zinc-700 bg-zinc-950/40 p-4 rounded-2xl transition-all duration-300">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full 
                                                @if($story->genre == 'Fantasy') bg-purple-500/10 text-purple-400 border border-purple-500/20
                                                @elseif($story->genre == 'Horror') bg-red-500/10 text-red-400 border border-red-500/20
                                                @elseif($story->genre == 'Romance') bg-pink-500/10 text-pink-400 border border-pink-500/20
                                                @elseif($story->genre == 'Comedy') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                                                @else bg-blue-500/10 text-blue-400 border border-blue-500/20 @endif">
                                                {{ $story->genre }}
                                            </span>
                                            <span class="text-[11px] text-zinc-500">{{ $story->updated_at->diffForHumans() }}</span>
                                        </div>
                                        <h3 class="font-bold text-zinc-100 group-hover:text-amber-400 transition-colors truncate">
                                            <a href="{{ route('stories.show', $story) }}">{{ $story->title }}</a>
                                        </h3>
                                        <p class="text-xs text-zinc-400 mt-1 line-clamp-1">
                                            {{ $story->description }}
                                        </p>
                                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-zinc-850/50 text-[10px] text-zinc-500">
                                            <span>Oleh: <strong class="text-zinc-400">{{ $story->user->name }}</strong></span>
                                            <span>✍️ {{ strlen($story->content) }} karakter</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-zinc-800/80 pt-6 text-center text-sm text-zinc-500">
                <p>&copy; {{ date('Y') }} matahariWrite. Dibuat dengan 🧡 untuk Penulis Kreatif.</p>
            </footer>
        </div>
    </body>
</html>
