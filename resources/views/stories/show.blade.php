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
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Papan Tulis Kolaborasi</span>
                        </span>
                        <span>✍️ Mulai mengetik, tulisan Anda akan disimpan secara otomatis</span>
                    </div>

                    <!-- Large Textarea for Story Writing -->
                    <textarea id="story-content" rows="22" placeholder="Mulailah menulis isi cerita kolaboratif ini di sini..." class="w-full flex-grow bg-zinc-950 border border-zinc-850 rounded-2xl p-6 text-zinc-100 placeholder-zinc-700 text-base leading-relaxed focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500/50 transition-all resize-none shadow-inner">{{ $story->content }}</textarea>
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
                                        <p class="text-xs text-zinc-450 leading-relaxed break-words">
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
            textarea.addEventListener('input', function() {
                saveStatus.innerHTML = '📝 Sedang mengetik...';
                saveStatus.className = 'px-3.5 py-1.5 text-xs font-semibold text-amber-400 bg-amber-500/5 border border-amber-500/15 rounded-xl transition-all duration-300';
                
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    saveContent();
                }, 1000); // Debounce 1 detik
            });

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
                            <p class="text-xs text-zinc-450 leading-relaxed break-words">
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
        });
    </script>

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
