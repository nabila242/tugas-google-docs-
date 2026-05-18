<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-bold text-2xl text-zinc-150 leading-tight">
                {{ __('Dashboard Cerita') }}
            </h2>
            <a href="{{ route('stories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tulis Cerita Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-zinc-950 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Search & Filters -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul cerita, genre, atau sinopsis..." class="block w-full pl-10 pr-3 py-2.5 border border-zinc-800 rounded-xl bg-zinc-950 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition text-sm">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-100 rounded-xl font-semibold text-sm transition">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 border border-zinc-800 hover:border-zinc-700 text-zinc-400 hover:text-zinc-200 rounded-xl text-center font-semibold text-sm transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Stories Grid -->
            @if($stories->isEmpty())
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-12 text-center text-zinc-500 space-y-4 shadow-xl">
                    <svg class="w-16 h-16 mx-auto stroke-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="text-lg font-semibold text-zinc-300">Tidak ada cerita ditemukan</h3>
                    <p class="text-sm text-zinc-500 max-w-sm mx-auto">
                        @if($search)
                            Kata kunci "{{ $search }}" tidak cocok dengan cerita manapun. Silakan coba kata kunci lain.
                        @else
                            Mulai langkah kolaborasi pertama Anda dengan membuat cerita baru sekarang!
                        @endif
                    </p>
                    @if(!$search)
                        <div class="pt-2">
                            <a href="{{ route('stories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/10">
                                Tulis Cerita Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stories as $story)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 hover:shadow-2xl transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                            <!-- Glow decoration -->
                            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition duration-300"></div>

                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full 
                                        @if($story->genre == 'Fantasy') bg-purple-500/10 text-purple-400 border border-purple-500/20
                                        @elseif($story->genre == 'Horror') bg-red-500/10 text-red-400 border border-red-500/20
                                        @elseif($story->genre == 'Romance') bg-pink-500/10 text-pink-400 border border-pink-500/20
                                        @elseif($story->genre == 'Comedy') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                                        @else bg-blue-500/10 text-blue-400 border border-blue-500/20 @endif">
                                        {{ $story->genre }}
                                    </span>
                                    <span class="text-xs text-zinc-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $story->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-zinc-100 group-hover:text-amber-400 transition-colors line-clamp-1">
                                    {{ $story->title }}
                                </h3>
                                <p class="text-sm text-zinc-400 mt-2 line-clamp-3 leading-relaxed">
                                    {{ $story->description }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-zinc-800/60 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-amber-400">
                                        {{ strtoupper(substr($story->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-zinc-400 truncate max-w-[120px]" title="{{ $story->user->name }}">
                                        {{ $story->user->name }}
                                    </span>
                                </div>
                                <a href="{{ route('stories.show', $story) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-zinc-200 hover:text-white bg-zinc-800 hover:bg-zinc-750 border border-zinc-800 hover:border-zinc-700 rounded-xl transition">
                                    <span>Tulis</span>
                                    <svg class="w-3.5 h-3.5 stroke-zinc-450 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $stories->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
