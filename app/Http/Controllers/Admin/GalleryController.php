<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.gallery.index', [
            'items' => GalleryItem::orderBy('position')->paginate(24),
        ]);
    }

    public function create()
    {
        return view('admin.gallery.form', [
            'item' => new GalleryItem(['format' => 'portrait', 'is_published' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);

        GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Réalisation ajoutée.');
    }

    public function edit(GalleryItem $item)
    {
        return view('admin.gallery.form', compact('item'));
    }

    public function update(Request $request, GalleryItem $item)
    {
        $item->update($this->validated($request));

        return redirect()->route('admin.gallery.index')->with('success', 'Réalisation mise à jour.');
    }

    public function destroy(GalleryItem $item)
    {
        $item->delete();

        return back()->with('success', 'Réalisation supprimée.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'format' => ['required', 'in:portrait,paysage'],
            'position' => ['nullable', 'integer', 'min:0'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:6144'],
        ], [], ['title' => 'titre', 'category' => 'univers', 'image' => 'photo']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('gallery', 'public');
        } else {
            unset($data['image']);
        }

        $data['is_published'] = $request->boolean('is_published');
        $data['position'] = (int) $request->input('position', 0);

        return $data;
    }
}
