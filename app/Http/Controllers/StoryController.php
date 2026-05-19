<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    /**
     * Tampilkan landing page dengan cerita terbaru.
     */
    public function welcome()
    {
        $latestStories = Story::with('user')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('welcome', compact('latestStories'));
    }

    /**
     * Tampilkan dashboard dengan daftar cerita.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $stories = Story::with('user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('genre', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest('updated_at')
            ->paginate(9);

        return view('dashboard', compact('stories', 'search'));
    }

    /**
     * Tampilkan form pembuatan cerita.
     */
    public function create()
    {
        $genres = ['Fantasy', 'Horror', 'Romance', 'Comedy', 'Action'];
        return view('stories.create', compact('genres'));
    }

    /**
     * Simpan cerita baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|in:Fantasy,Horror,Romance,Comedy,Action',
            'description' => 'required|string|max:1000',
        ]);

        $story = Auth::user()->stories()->create([
            'title' => $request->title,
            'genre' => $request->genre,
            'description' => $request->description,
            'content' => '', // Mulai dengan cerita kosong
        ]);

        return redirect()->route('stories.show', $story)
            ->with('success', 'Cerita berhasil dibuat! Mari mulai menulis.');
    }

    /**
     * Tampilkan halaman editor cerita (kolaboratif).
     */
    public function show(Story $story)
    {
        $story->load(['user', 'comments.user']);
        return view('stories.show', compact('story'));
    }

    /**
     * Simpan perubahan cerita (digunakan untuk fallback manual/auto save).
     */
    public function update(Request $request, Story $story)
    {
        $request->validate([
            'content' => 'nullable|string',
        ]);

        $story->update([
            'content' => $request->content ?? '',
        ]);

        // Logika pembuatan riwayat versi (Fase 5)
        $lastVersion = $story->versions()->first();
        $shouldCreateVersion = false;

        if (!$lastVersion) {
            $shouldCreateVersion = true;
        } else {
            // Cek jika penulisnya berbeda
            if ($lastVersion->user_id !== Auth::id()) {
                $shouldCreateVersion = true;
            } 
            // Atau jika versi terakhir disimpan lebih dari 2 menit yang lalu, dan isinya berubah
            elseif ($lastVersion->created_at->lt(now()->subMinutes(2)) && $lastVersion->content !== $story->content) {
                $shouldCreateVersion = true;
            }
        }

        if ($shouldCreateVersion && ($story->content ?? '') !== '') {
            $story->versions()->create([
                'user_id' => Auth::id(),
                'content' => $story->content,
            ]);
        }

        broadcast(new \App\Events\StoryContentUpdated($story->id, $story->content, Auth::id()))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Cerita berhasil disimpan.',
        ]);
    }

    /**
     * Ambil riwayat versi cerita (Fase 5).
     */
    public function getVersions(Story $story)
    {
        $versions = $story->versions()
            ->with('user')
            ->get()
            ->map(function ($version) {
                return [
                    'id' => $version->id,
                    'user_name' => $version->user->name,
                    'content' => $version->content,
                    'length' => strlen($version->content),
                    'time_ago' => $version->created_at->diffForHumans(),
                    'created_at' => $version->created_at->format('d M Y, H:i'),
                ];
            });

        return response()->json([
            'status' => 'success',
            'versions' => $versions,
        ]);
    }

    /**
     * Pulihkan cerita ke versi tertentu (Fase 5).
     */
    public function restoreVersion(Story $story, $versionId)
    {
        $version = $story->versions()->findOrFail($versionId);

        $story->update([
            'content' => $version->content,
        ]);

        // Picu siaran sinkronisasi agar layar kolaborator lain langsung berubah!
        broadcast(new \App\Events\StoryContentUpdated($story->id, $story->content, Auth::id()))->toOthers();

        // Buat versi baru saat memulihkan, sebagai penanda riwayat pemulihan
        $story->versions()->create([
            'user_id' => Auth::id(),
            'content' => $story->content,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cerita berhasil dipulihkan ke versi pilihan Anda.',
            'content' => $story->content,
        ]);
    }
}
