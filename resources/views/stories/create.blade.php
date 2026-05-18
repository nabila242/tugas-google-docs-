<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-zinc-150 leading-tight">
            {{ __('Tulis Cerita Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-zinc-950 min-h-[calc(100vh-65px)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
                <!-- Glow decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl"></div>

                <div class="mb-6 pb-4 border-b border-zinc-800">
                    <h3 class="text-lg font-bold text-zinc-100">Detail Cerita</h3>
                    <p class="text-sm text-zinc-500 mt-1">Isi formulir berikut untuk memulai kolaborasi penulisan cerita Anda.</p>
                </div>

                <form action="{{ route('stories.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Judul -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-semibold text-zinc-300">Judul Cerita</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Contoh: Petualangan Menuju Matahari" class="block w-full px-4 py-3 border border-zinc-800 rounded-xl bg-zinc-950 text-zinc-100 placeholder-zinc-650 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition text-sm @error('title') border-red-500/50 focus:ring-red-500/30 focus:border-red-500 @enderror">
                        @error('title')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Genre -->
                    <div class="space-y-2">
                        <label for="genre" class="block text-sm font-semibold text-zinc-300">Genre</label>
                        <select name="genre" id="genre" class="block w-full px-4 py-3 border border-zinc-800 rounded-xl bg-zinc-950 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition text-sm @error('genre') border-red-500/50 focus:ring-red-500/30 focus:border-red-500 @enderror">
                            <option value="" disabled selected>Pilih genre cerita...</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre }}" {{ old('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                            @endforeach
                        </select>
                        @error('genre')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-semibold text-zinc-300">Sinopsis / Deskripsi Singkat</label>
                        <textarea name="description" id="description" rows="4" placeholder="Tuliskan gambaran singkat cerita Anda agar penulis lain tertarik untuk berkolaborasi..." class="block w-full px-4 py-3 border border-zinc-800 rounded-xl bg-zinc-950 text-zinc-100 placeholder-zinc-650 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition text-sm resize-none @error('description') border-red-500/50 focus:ring-red-500/30 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-800">
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 border border-zinc-800 hover:border-zinc-700 text-zinc-400 hover:text-zinc-200 rounded-xl font-semibold text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 font-semibold text-zinc-950 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 rounded-xl transition shadow-lg shadow-orange-500/10">
                            Mulai Cerita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
