<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JournalController extends Controller
{
    public function index()
    {
        return view('admin.journal.index', ['posts' => Post::orderByDesc('published_at')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.journal.form', [
            'post' => new Post(['is_published' => true, 'reading_time' => 3, 'category' => 'Conseils']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(4));

        Post::create($data);

        return redirect()->route('admin.journal.index')->with('success', 'Article publié.');
    }

    public function edit(Post $post)
    {
        return view('admin.journal.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request));

        return redirect()->route('admin.journal.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Article supprimé.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:60'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:60'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:6144'],
        ], [], ['title' => 'titre', 'body' => 'contenu', 'category' => 'rubrique']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('journal', 'public');
        } else {
            unset($data['image']);
        }

        $data['is_published'] = $request->boolean('is_published');
        $data['reading_time'] = (int) $request->input('reading_time', 3);
        $data['published_at'] = $request->input('published_at') ?: now();

        return $data;
    }
}
