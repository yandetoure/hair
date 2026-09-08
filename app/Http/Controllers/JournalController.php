<?php

namespace App\Http\Controllers;

use App\Models\Post;

class JournalController extends Controller
{
    public function index()
    {
        return view('public.journal.index', [
            'posts' => Post::published()->orderByDesc('published_at')->get(),
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        return view('public.journal.show', [
            'post' => $post,
            'others' => Post::published()->whereKeyNot($post->id)->orderByDesc('published_at')->take(2)->get(),
        ]);
    }
}
