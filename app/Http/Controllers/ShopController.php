<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Product::active()->distinct()->orderBy('category')->pluck('category');

        $products = Product::active()
            ->when($request->filled('categorie'), fn ($q) => $q->where('category', $request->string('categorie')))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('public.shop.show', [
            'product' => $product,
            'related' => Product::active()
                ->where('category', $product->category)
                ->whereKeyNot($product->id)
                ->take(3)
                ->get(),
        ]);
    }
}
