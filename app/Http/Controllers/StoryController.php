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

        broadcast(new \App\Events\StoryContentUpdated($story->id, $story->content, Auth::id()))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Cerita berhasil disimpan.',
        ]);
    }
}
