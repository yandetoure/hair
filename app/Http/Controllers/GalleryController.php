<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = GalleryItem::published()->distinct()->orderBy('category')->pluck('category');

        return view('public.gallery', [
            'categories' => $categories,
            'items' => GalleryItem::published()
                ->when($request->filled('univers'), fn ($q) => $q->where('category', $request->string('univers')))
                ->orderBy('position')
                ->get(),
            'active' => $request->string('univers')->toString(),
        ]);
    }
}
