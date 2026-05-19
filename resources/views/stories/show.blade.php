@php
    $canEdit = $story->canEdit(Auth::id());
    $isOwner = Auth::id() === $story->user_id;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="text-zinc-450 hover:text-zinc-200 transition text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <span class="text-zinc-700">/</span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full 
                        @if($story->genre == 'Fantasy') bg-purple-500/10 text-purple-400 border border-purple-500/20
                        @elseif($story->genre == 'Horror') bg-red-500/10 text-red-400 border border-red-500/20
                        @elseif($story->genre == 'Romance') bg-pink-500/10 text-pink-400 border border-pink-500/20
                        @elseif($story->genre == 'Comedy') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                        @else bg-blue-500/10 text-blue-400 border border-blue-500/20 @endif">
                        {{ $story->genre }}
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-zinc-100 leading-tight">
                    {{ $story->title }}
                </h2>
            </div>
            <!-- Auto-save Status Indicator -->
            <div class="flex items-center gap-2">
                <span id="save-status" class="px-3.5 py-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/5 border border-emerald-500/15 rounded-xl transition-all duration-300">
                    ✅ Tersimpan
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-zinc-950 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-8 items-start">
                
                <!-- Main Editor Area (8 Cols) -->
                <div class="lg:col-span-8 bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-2xl relative overflow-hidden flex flex-col min-h-[600px]">
                    <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500"></div>
                    
                    <div class="mb-4 flex items-center justify-between text-xs text-zinc-500 border-b border-zinc-800 pb-3">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full @if($canEdit) bg-emerald-500 animate-pulse @else bg-amber-500 @endif"></span>
                            <span>Papan Tulis Kolaborasi</span>
                        </span>
                        @if($canEdit)
                            <span>✍️ Mulai mengetik, tulisan Anda akan disimpan secara otomatis</span>
                        @else
                            <span class="px-2.5 py-1 bg-zinc-950 border border-zinc-850 text-amber-400 rounded-full font-bold flex items-center gap-1 shadow-sm">
                                <span>🔒 Mode Membaca</span>
                            </span>
                        @endif
                    </div>

                    <!-- Large Textarea for Story Writing -->
                    <textarea id="story-content" rows="22" 
                        @if(!$canEdit) disabled placeholder="👀 Mode Membaca — Anda tidak memiliki izin untuk mengedit cerita ini. Silakan hubungi pemilik untuk ditambahkan sebagai kolaborator." @else placeholder="Mulailah menulis isi cerita kolaboratif ini di sini..." @endif
                        class="w-full flex-grow bg-zinc-950 border border-zinc-850 rounded-2xl p-6 text-zinc-100 placeholder-zinc-700 text-base leading-relaxed focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500/50 transition-all resize-none shadow-inner @if(!$canEdit) opacity-60 cursor-not-allowed @endif">{{ $story->content }}</textarea>
                </div>

                <!-- Sidebar Area (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Detail & Sinopsis -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl relative">
                        <h3 class="font-bold text-zinc-200 mb-3 text-sm tracking-wide uppercase">Sinopsis</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            {{ $story->description }}
                        </p>
                        <div class="mt-4 pt-4 border-t border-zinc-800 flex justify-between items-center text-xs text-zinc-500">
                            <span>Pembuat: <strong class="text-zinc-400">{{ $story->user->name }}</strong></span>
                            <span>Dibuat: <strong>{{ $story->created_at->format('d M Y') }}</strong></span>
                        </div>
                    </div>

                    <!-- Target Menulis & Live Analytics (Fase 7) -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl space-y-5 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-amber-500 to-rose-500"></div>
                        
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-zinc-200 text-sm tracking-wide uppercase flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Statistik & Target</span>
                            </h3>
                            <!-- Target Reached Badge -->
                            <span id="goal-reached-badge" class="hidden px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 animate-pulse flex items-center gap-1">
                                <span>🎯 Target Tercapai!</span>
                            </span>
                        </div>

                        <!-- Mini stats grid -->
                        <div class="grid grid-cols-3 gap-3 bg-zinc-950/40 p-3 rounded-2xl border border-zinc-850">
                            <div class="text-center">
                                <span class="block text-zinc-200 text-[10px] uppercase font-bold tracking-wider">Kata</span>
                                <span id="stat-words" class="text-zinc-100 font-extrabold text-base transition-all duration-300">0</span>
                            </div>
                            <div class="text-center border-x border-zinc-850/60">
                                <span class="block text-zinc-200 text-[10px] uppercase font-bold tracking-wider">Karakter</span>
                                <span id="stat-chars" class="text-zinc-100 font-extrabold text-base transition-all duration-300">0</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-zinc-200 text-[10px] uppercase font-bold tracking-wider">Baca</span>
                                <span id="stat-reading" class="text-zinc-100 font-extrabold text-base transition-all duration-300">1 m</span>
                            </div>
                        </div>

                        <!-- Progress Bar & Target Goal Settings -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-300">Progres Target:</span>
                                <div class="flex items-center gap-1.5 font-bold">
                                    <span id="goal-progress-text" class="text-zinc-200">0 / 0 kata</span>
                                    <span id="goal-progress-percent" class="text-zinc-400 font-semibold">(0%)</span>
                                </div>
                            </div>
                            
                            <!-- Glowing Progress Bar track -->
                            <div class="w-full h-3 bg-zinc-950 rounded-full overflow-hidden p-[1px] border border-zinc-850">
                                <div id="goal-progress-bar" class="h-full w-0 bg-gradient-to-r from-amber-500 to-rose-500 rounded-full transition-all duration-500 shadow-[0_0_8px_rgba(245,158,11,0.2)]"></div>
                            </div>

                            <!-- Target Settings Button (Owner/Editor only) -->
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-[10px] text-zinc-400">Target Menulis: <strong id="current-word-goal-label" class="text-zinc-300">{{ $story->word_goal > 0 ? $story->word_goal : 'Tidak diatur' }}</strong></span>
                                @if($canEdit)
                                    <button type="button" id="btn-toggle-goal-form" class="text-[10px] font-bold text-amber-500 hover:text-amber-400 flex items-center gap-1 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Atur Target</span>
                                    </button>
                                @endif
                            </div>

                            <!-- Goal Setting Form Inline -->
                            @if($canEdit)
                                <div id="goal-setting-form-container" class="hidden pt-2 border-t border-zinc-850/60 transition-all duration-300">
                                    <form id="form-update-goal" class="flex gap-2">
                                        @csrf
                                        <input type="number" id="input-word-goal" name="word_goal" placeholder="Target kata (cth: 500)" value="{{ $story->word_goal }}" min="0" max="100000" class="flex-grow bg-zinc-950 border border-zinc-850 rounded-xl px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-rose-500 transition">
                                        <button type="submit" id="btn-save-goal" class="bg-rose-500 hover:bg-rose-400 text-zinc-950 font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-lg shadow-rose-500/10">
                                            Simpan
                                        </button>
                                    </form>
                                    <span id="goal-save-error" class="hidden text-[9px] text-red-400 font-semibold mt-1 block"></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Online/Active Participants -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl">
                        <h3 class="font-bold text-zinc-200 mb-4 text-sm tracking-wide uppercase flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-lg shadow-amber-500/50 animate-ping"></span>
                            <span>Penulis Aktif</span>
                        </h3>
                        <div id="active-users-list" class="space-y-3">
                            <!-- Owner -->
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-sm border border-amber-500/20">
                                        {{ strtoupper(substr($story->user->name, 0, 1)) }}
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-zinc-900"></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-zinc-200">{{ $story->user->name }}</span>
                                    <span class="text-[10px] text-zinc-500">Pemilik Cerita</span>
                                </div>
                            </div>
                            <!-- Current User (if different from owner) -->
                            @if(Auth::id() != $story->user_id)
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-400 flex items-center justify-center font-bold text-sm border border-orange-500/20">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-zinc-900"></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-zinc-200">{{ Auth::user()->name }} (Anda)</span>
                                        <span class="text-[10px] text-zinc-500">Kolaborator</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Collaborators Card (Fase 6) -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl space-y-4">
                        <h3 class="font-bold text-zinc-200 text-sm tracking-wide uppercase flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Kolaborator</span>
                        </h3>
                        
                        <!-- List of current collaborators -->
                        <div class="space-y-3">
                            <!-- Owner -->
                            <div class="flex items-center justify-between text-xs bg-zinc-950/40 p-2.5 rounded-xl border border-zinc-850">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-[10px] border border-amber-500/20">
                                        {{ strtoupper(substr($story->user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-zinc-300">{{ $story->user->name }}</span>
                                </div>
                                <span class="px-2 py-0.5 text-[9px] font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-full">Pemilik</span>
                            </div>

                            <!-- Invited Collaborators -->
                            @if($story->collaborators->isEmpty())
                                <div class="text-center py-2 text-zinc-500 text-xs italic">
                                    Belum ada editor tambahan.
                                </div>
                            @else
                                @foreach($story->collaborators as $collab)
                                    <div class="flex items-center justify-between text-xs bg-zinc-950/20 p-2.5 rounded-xl border border-zinc-850">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-bold text-[10px] border border-purple-500/20">
                                                {{ strtoupper(substr($collab->user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-zinc-300">{{ $collab->user->name }}</span>
                                                <span class="text-[9px] text-zinc-500">{{ $collab->user->email }}</span>
                                            </div>
                                        </div>

                                        @if($isOwner)
                                            <form action="{{ route('stories.collaborators.destroy', [$story->id, $collab->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus akses edit kolaborator ini?')" class="text-red-500 hover:text-red-400 p-1 hover:bg-zinc-800 rounded-lg transition" title="Hapus Kolaborator">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2 py-0.5 text-[9px] font-bold bg-purple-500/10 border border-purple-500/20 text-purple-400 rounded-full">Editor</span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Owner-only invitation form -->
                        @if($isOwner)
                            <div class="pt-2 border-t border-zinc-850">
                                <form action="{{ route('stories.collaborators.store', $story->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 block">Undang Editor Baru</label>
                                    <div class="flex gap-2">
                                        <input type="email" name="email" placeholder="email@kolaborator.com" required class="flex-grow bg-zinc-950 border border-zinc-850 rounded-xl px-3 py-1.5 text-xs text-zinc-100 placeholder-zinc-700 focus:outline-none focus:border-amber-500 transition">
                                        <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-lg shadow-amber-500/10">
                                            Tambah
                                        </button>
                                    </div>
                                    @if(session('success'))
                                        <p class="text-[10px] text-emerald-400 font-semibold mt-1">✓ {{ session('success') }}</p>
                                    @endif
                                    @if(session('error'))
                                        <p class="text-[10px] text-red-400 font-semibold mt-1">✗ {{ session('error') }}</p>
                                    @endif
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Version History Widget (Fase 5) -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl space-y-4">
                        <h3 class="font-bold text-zinc-200 text-sm tracking-wide uppercase flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Riwayat Versi</span>
                        </h3>
                        <p class="text-xs text-zinc-500">Lihat versi penulisan sebelumnya atau pulihkan isi cerita Anda.</p>
                        <button type="button" id="btn-open-versions" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center border border-zinc-800 hover:border-zinc-700 bg-zinc-950 text-zinc-300 hover:text-zinc-150 transition shadow-inner flex items-center justify-center gap-2">
                            📜 Buka Riwayat Versi
                        </button>
                    </div>

                    <!-- Comment Section -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl flex flex-col max-h-[500px]">
                        <h3 class="font-bold text-zinc-200 mb-4 text-sm tracking-wide uppercase flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <span>Diskusi Cerita</span>
                        </h3>

                        <!-- Comments List -->
                        <div id="comments-list" class="flex-grow overflow-y-auto space-y-4 pr-1 max-h-[220px] mb-4 custom-scrollbar">
                            @if($story->comments->isEmpty())
                                <div class="text-center py-6 text-zinc-500 text-xs">
                                    Belum ada diskusi. Mulai diskusikan alur ceritanya!
                                </div>
                            @else
                                @foreach($story->comments as $comment)
                                    <div class="bg-zinc-950/50 border border-zinc-800 p-3 rounded-2xl space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-zinc-300">{{ $comment->user->name }}</span>
                                            <span class="text-[9px] text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-zinc-100 leading-relaxed break-words">
                                            {{ $comment->comment }}
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Write Comment Form -->
                        <form action="{{ route('comments.store', $story) }}" method="POST" class="border-t border-zinc-850 pt-4 space-y-3">
                            @csrf
                            <textarea name="comment" rows="2" placeholder="Tulis masukan atau ide cerita..." class="w-full px-3 py-2 border border-zinc-800 rounded-xl bg-zinc-950 text-zinc-100 placeholder-zinc-700 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500/50 resize-none shadow-inner" required></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 font-semibold text-xs text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/10">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Auto-save & Real-Time Sync Debounce JavaScript Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeout = null;
            const textarea = document.getElementById('story-content');
            const saveStatus = document.getElementById('save-status');

            // Ambil ID User dan ID Cerita dari Laravel
            const currentUserId = {{ Auth::id() }};
            const storyId = {{ $story->id }};
            const storyOwnerId = {{ $story->user_id }};
            const activeUsersList = document.getElementById('active-users-list');
            const commentsList = document.getElementById('comments-list');

            // Format inisial nama
            function getInitial(name) {
                return name.substring(0, 1).toUpperCase();
            }

            // Update Tampilan User Online
            function updateOnlineUsers(users) {
                activeUsersList.innerHTML = '';
                users.forEach(user => {
                    const isOwner = user.id === storyOwnerId;
                    const isMe = user.id === currentUserId;
                    
                    const userDiv = document.createElement('div');
                    userDiv.className = 'flex items-center gap-3';
                    userDiv.innerHTML = `
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-sm border border-amber-500/20">
                                ${getInitial(user.name)}
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-zinc-900"></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-zinc-200">${user.name} ${isMe ? '(Anda)' : ''}</span>
                            <span class="text-[10px] text-zinc-500">${isOwner ? 'Pemilik Cerita' : 'Kolaborator'}</span>
                        </div>
                    `;
                    activeUsersList.appendChild(userDiv);
                });
            }

            // Input listener untuk Auto-Save
            if (textarea && !textarea.disabled) {
                textarea.addEventListener('input', function() {
                    saveStatus.innerHTML = '📝 Sedang mengetik...';
                    saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-amber-400 bg-emerald-500/5 border border-emerald-500/15 rounded-xl transition-all duration-300';
                    
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        saveContent();
                    }, 1000); // Debounce 1 detik
                });
            } else {
                saveStatus.innerHTML = '🔒 Mode Membaca';
                saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-amber-400 bg-amber-500/5 border border-amber-500/15 rounded-xl transition-all duration-300';
            }

            function saveContent() {
                saveStatus.innerHTML = '⏳ Menyimpan...';
                saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-orange-400 bg-orange-500/5 border border-orange-500/15 rounded-xl transition-all duration-300';
                
                fetch('{{ route("stories.update", $story) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        content: textarea.value
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal menyimpan ke server');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        saveStatus.innerHTML = '✅ Tersimpan';
                        saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/5 border border-emerald-500/15 rounded-xl transition-all duration-300';
                    } else {
                        saveStatus.innerHTML = '❌ Gagal Menyimpan';
                        saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-red-400 bg-red-500/5 border border-red-500/15 rounded-xl transition-all duration-300';
                    }
                })
                .catch(error => {
                    console.error(error);
                    saveStatus.innerHTML = '❌ Kesalahan Jaringan';
                    saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-red-400 bg-red-500/5 border border-red-500/15 rounded-xl transition-all duration-300';
                });
            }

            // Hubungkan ke Channel Reverb (Websocket)
            if (window.Echo) {
                window.Echo.join(`stories.${storyId}`)
                    .here((users) => {
                        window.onlineUsers = users;
                        updateOnlineUsers(window.onlineUsers);
                    })
                    .joining((user) => {
                        if (!window.onlineUsers.some(u => u.id === user.id)) {
                            window.onlineUsers.push(user);
                            updateOnlineUsers(window.onlineUsers);
                        }
                    })
                    .leaving((user) => {
                        window.onlineUsers = window.onlineUsers.filter(u => u.id !== user.id);
                        updateOnlineUsers(window.onlineUsers);
                    })
                    .listen('StoryContentUpdated', (e) => {
                        if (e.userId !== currentUserId) {
                            textarea.value = e.content;
                            saveStatus.innerHTML = '✨ Tersinkronisasi';
                            saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-sky-400 bg-sky-500/5 border border-sky-500/15 rounded-xl transition-all duration-300';
                            
                            // Recalculate stats for Fase 7!
                            updateStats();

                            setTimeout(() => {
                                saveStatus.innerHTML = '✅ Tersimpan';
                                saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/5 border border-emerald-500/15 rounded-xl transition-all duration-300';
                            }, 1500);
                        }
                    })
                    .listen('CommentSent', (e) => {
                        // Buat komentar baru di sidebar secara instan
                        const newCommentDiv = document.createElement('div');
                        newCommentDiv.className = 'bg-zinc-950/50 border border-zinc-800 p-3 rounded-2xl space-y-1.5';
                        newCommentDiv.innerHTML = `
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-zinc-300">${e.userName}</span>
                                <span class="text-[9px] text-zinc-500">Baru saja</span>
                            </div>
                            <p class="text-xs text-zinc-100 leading-relaxed break-words">
                                ${e.comment.comment}
                            </p>
                        `;
                        
                        // Hapus tulisan "belum ada diskusi"
                        const emptyState = commentsList.querySelector('.text-center');
                        if (emptyState) {
                            commentsList.innerHTML = '';
                        }
                        
                        commentsList.appendChild(newCommentDiv);
                        commentsList.scrollTop = commentsList.scrollHeight;
                    });
            }

            // JavaScript Riwayat Versi (Fase 5)
            const versionsModal = document.getElementById('versions-modal');
            const btnOpenVersions = document.getElementById('btn-open-versions');
            const btnCloseVersions = document.getElementById('btn-close-versions');
            const versionsTimelineList = document.getElementById('versions-timeline-list');
            const versionContentPreview = document.getElementById('version-content-preview');
            const btnRestoreVersion = document.getElementById('btn-restore-version');

            let selectedVersionId = null;

            btnOpenVersions.addEventListener('click', function() {
                versionsModal.classList.remove('hidden');
                loadVersions();
            });

            btnCloseVersions.addEventListener('click', function() {
                versionsModal.classList.add('hidden');
                selectedVersionId = null;
                versionContentPreview.textContent = 'Pilih versi di sebelah kiri untuk melihat isi pratinjau cerita...';
                btnRestoreVersion.disabled = true;
                btnRestoreVersion.classList.add('cursor-not-allowed');
            });

            function loadVersions() {
                versionsTimelineList.innerHTML = '<div class="text-center py-6 text-zinc-500 text-xs">⏳ Memuat riwayat versi...</div>';
                
                fetch(`/stories/${storyId}/versions`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderVersionsList(data.versions);
                    }
                })
                .catch(err => {
                    console.error(err);
                    versionsTimelineList.innerHTML = '<div class="text-center py-6 text-red-500 text-xs">❌ Gagal memuat riwayat versi</div>';
                });
            }

            function renderVersionsList(versions) {
                if (versions.length === 0) {
                    versionsTimelineList.innerHTML = '<div class="text-center py-6 text-zinc-500 text-xs">Belum ada riwayat versi tersimpan. Teruslah menulis untuk mencatat perubahan!</div>';
                    return;
                }

                versionsTimelineList.innerHTML = '';
                versions.forEach((ver, index) => {
                    const verItem = document.createElement('div');
                    verItem.className = 'p-3 bg-zinc-950/40 border border-zinc-850 hover:border-purple-500/50 rounded-2xl cursor-pointer transition flex items-start gap-3 relative';
                    verItem.dataset.id = ver.id;
                    
                    const markerColor = index === 0 ? 'bg-emerald-500 shadow-emerald-500/50' : 'bg-purple-500 shadow-purple-500/50';
                    const markerLabel = index === 0 ? 'Terbaru' : `Versi #${versions.length - index}`;

                    verItem.innerHTML = `
                        <div class="relative pt-1">
                            <span class="w-2 h-2 rounded-full ${markerColor} shadow-lg block"></span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-zinc-200">${ver.user_name}</span>
                                <span class="px-2 py-0.5 text-[9px] font-semibold bg-zinc-900 border border-zinc-800 text-zinc-400 rounded-full">${markerLabel}</span>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-[10px] text-zinc-500">${ver.time_ago}</span>
                                <span class="text-[10px] text-purple-400">${ver.length} karakter</span>
                            </div>
                        </div>
                    `;

                    verItem.addEventListener('click', function() {
                        // Reset all borders
                        versionsTimelineList.querySelectorAll('div').forEach(el => el.classList.remove('border-purple-500'));
                        verItem.classList.add('border-purple-500');
                        
                        // Select version
                        selectedVersionId = ver.id;
                        versionContentPreview.textContent = ver.content;
                        
                        btnRestoreVersion.disabled = false;
                        btnRestoreVersion.classList.remove('cursor-not-allowed');
                    });

                    versionsTimelineList.appendChild(verItem);
                });
            }

            btnRestoreVersion.addEventListener('click', function() {
                if (!selectedVersionId) return;

                btnRestoreVersion.innerHTML = '⏳ Memulihkan...';
                btnRestoreVersion.disabled = true;

                fetch(`/stories/${storyId}/versions/${selectedVersionId}/restore`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        textarea.value = data.content;
                        
                        // Recalculate stats for Fase 7!
                        updateStats();
                        
                        // Update status
                        saveStatus.innerHTML = '✨ Versi Dipulihkan';
                        saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/5 border border-emerald-500/15 rounded-xl transition-all duration-300';
                        
                        // Close modal
                        btnCloseVersions.click();
                        
                        setTimeout(() => {
                            saveStatus.innerHTML = '✅ Tersimpan';
                            saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/5 border border-emerald-500/15 rounded-xl transition-all duration-300';
                        }, 2000);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memulihkan versi cerita.');
                    btnRestoreVersion.innerHTML = 'Pulihkan ke Versi Ini';
                    btnRestoreVersion.disabled = false;
                });
            });

            // ==========================================
            // LOGIKA STATISTIK & TARGET MENULIS (FASE 7)
            // ==========================================
            let wordGoal = {{ (int) $story->word_goal }};
            const statWords = document.getElementById('stat-words');
            const statChars = document.getElementById('stat-chars');
            const statReading = document.getElementById('stat-reading');
            const goalProgressText = document.getElementById('goal-progress-text');
            const goalProgressPercent = document.getElementById('goal-progress-percent');
            const goalProgressBar = document.getElementById('goal-progress-bar');
            const goalReachedBadge = document.getElementById('goal-reached-badge');

            function countWords(str) {
                if (!str || str.trim() === '') return 0;
                return str.trim().split(/\s+/).length;
            }

            function updateStats() {
                const text = textarea ? textarea.value : '';
                const wordCount = countWords(text);
                const charCount = text.length;
                
                // Estimasi membaca: rata-rata 200 kata per menit
                const readingTime = Math.max(1, Math.ceil(wordCount / 200));

                // Update UI Statistik
                if (statWords) statWords.innerText = wordCount.toLocaleString();
                if (statChars) statChars.innerText = charCount.toLocaleString();
                if (statReading) statReading.innerText = readingTime + ' m';

                // Update Progress Target
                if (wordGoal > 0) {
                    const percent = Math.min(100, Math.round((wordCount / wordGoal) * 100));
                    
                    if (goalProgressText) goalProgressText.innerText = `${wordCount.toLocaleString()} / ${wordGoal.toLocaleString()} kata`;
                    if (goalProgressPercent) goalProgressPercent.innerText = `(${percent}%)`;
                    
                    if (goalProgressBar) {
                        goalProgressBar.style.width = `${percent}%`;
                        
                        // Perubahan warna progress bar jika target tercapai!
                        if (percent >= 100) {
                            goalProgressBar.className = 'h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-500 shadow-[0_0_12px_rgba(16,185,129,0.4)]';
                            if (goalReachedBadge) goalReachedBadge.classList.remove('hidden');
                        } else {
                            goalProgressBar.className = 'h-full bg-gradient-to-r from-amber-500 to-rose-500 rounded-full transition-all duration-500 shadow-[0_0_8px_rgba(245,158,11,0.2)]';
                            if (goalReachedBadge) goalReachedBadge.classList.add('hidden');
                        }
                    }
                } else {
                    if (goalProgressText) goalProgressText.innerText = `${wordCount.toLocaleString()} kata`;
                    if (goalProgressPercent) goalProgressPercent.innerText = '';
                    if (goalProgressBar) {
                        goalProgressBar.style.width = '0%';
                        goalProgressBar.className = 'h-full bg-gradient-to-r from-amber-500 to-rose-500 rounded-full transition-all';
                    }
                    if (goalReachedBadge) goalReachedBadge.classList.add('hidden');
                }
            }

            // Panggil inisialisasi awal
            updateStats();

            // Panggil setiap kali pengguna mengetik
            if (textarea) {
                textarea.addEventListener('input', updateStats);
            }

            // Form update target menulis inline
            const btnToggleGoalForm = document.getElementById('btn-toggle-goal-form');
            const goalSettingFormContainer = document.getElementById('goal-setting-form-container');
            const formUpdateGoal = document.getElementById('form-update-goal');
            const inputWordGoal = document.getElementById('input-word-goal');
            const currentWordGoalLabel = document.getElementById('current-word-goal-label');
            const goalSaveError = document.getElementById('goal-save-error');

            if (btnToggleGoalForm) {
                btnToggleGoalForm.addEventListener('click', function() {
                    goalSettingFormContainer.classList.toggle('hidden');
                    if (!goalSettingFormContainer.classList.contains('hidden')) {
                        inputWordGoal.focus();
                    }
                });
            }

            if (formUpdateGoal) {
                formUpdateGoal.addEventListener('submit', function(e) {
                    e.preventDefault();
                    goalSaveError.classList.add('hidden');
                    
                    const newGoal = parseInt(inputWordGoal.value) || 0;

                    fetch(`/stories/${storyId}/goal`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            word_goal: newGoal
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            wordGoal = data.word_goal;
                            currentWordGoalLabel.textContent = wordGoal > 0 ? wordGoal : 'Tidak diatur';
                            goalSettingFormContainer.classList.add('hidden');
                            updateStats();
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        goalSaveError.innerText = err.message || 'Gagal menyimpan target menulis.';
                        goalSaveError.classList.remove('hidden');
                    });
                });
            }
        });
    </script>

    <!-- Version History Modal Overlay (Fase 5) -->
    <div id="versions-modal" class="fixed inset-0 z-50 hidden bg-zinc-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-zinc-900 border border-zinc-800 w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl relative">
            <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-purple-500 to-indigo-500"></div>
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-zinc-850 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-zinc-100 flex items-center gap-2">
                        <span>📜 Riwayat Versi Cerita</span>
                    </h3>
                    <p class="text-xs text-zinc-500 mt-1">Daftar riwayat perubahan teks cerita ini oleh kolaborator.</p>
                </div>
                <button id="btn-close-versions" class="p-2 hover:bg-zinc-800 rounded-xl transition text-zinc-400 hover:text-zinc-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 flex flex-col md:flex-row gap-6 max-h-[500px] overflow-hidden">
                <!-- Version Timeline List -->
                <div class="w-full md:w-1/2 flex flex-col">
                    <h4 class="text-xs font-bold uppercase text-zinc-550 mb-3 tracking-wider">Garis Waktu Versi</h4>
                    <div id="versions-timeline-list" class="flex-grow overflow-y-auto space-y-3 pr-1 max-h-[300px] custom-scrollbar">
                        <!-- Diisi dinamis via JavaScript -->
                    </div>
                </div>

                <!-- Preview Area -->
                <div class="w-full md:w-1/2 flex flex-col border-t md:border-t-0 md:border-l border-zinc-850 pt-4 md:pt-0 md:pl-6">
                    <h4 class="text-xs font-bold uppercase text-zinc-550 mb-3 tracking-wider">Pratinjau Isi</h4>
                    <div class="flex-grow bg-zinc-950 border border-zinc-850 rounded-2xl p-4 overflow-y-auto max-h-[220px] custom-scrollbar">
                        <pre id="version-content-preview" class="text-zinc-300 text-xs font-mono leading-relaxed whitespace-pre-wrap">Pilih versi di sebelah kiri untuk melihat isi pratinjau cerita...</pre>
                    </div>
                    @if($canEdit)
                    <div class="mt-4 flex gap-3">
                        <button id="btn-restore-version" disabled class="flex-grow py-2.5 px-4 font-semibold text-xs text-zinc-950 bg-gradient-to-r from-purple-400 to-indigo-500 hover:from-purple-300 hover:to-indigo-400 disabled:from-zinc-800 disabled:to-zinc-850 disabled:text-zinc-600 rounded-xl transition shadow-lg shadow-indigo-500/10 cursor-not-allowed">
                            Pulihkan ke Versi Ini
                        </button>
                    </div>
                    @else
                    <div class="mt-4 flex gap-3">
                        <div class="text-center w-full py-2.5 px-4 text-zinc-500 text-xs italic bg-zinc-950 border border-zinc-850 rounded-xl">
                            👀 Mode Hanya Baca — Tidak Bisa Memulihkan
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Scrollbar Style -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }
    </style>
</x-app-layout>
