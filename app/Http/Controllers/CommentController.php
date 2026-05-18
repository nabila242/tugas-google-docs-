<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Simpan komentar baru untuk suatu cerita.
     */
    public function store(Request $request, Story $story)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $story->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->route('stories.show', $story)
            ->with('success', 'Komentar berhasil ditambahkan!');
    }
}
