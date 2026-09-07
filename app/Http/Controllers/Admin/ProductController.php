<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.products.index', [
            'products' => Product::query()
                ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
                ->orderBy('category')
                ->orderBy('name')
                ->paginate(25)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product(['is_active' => true, 'stock' => 10])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));
        $data['image'] = $this->storeImage($request);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($image = $this->storeImage($request)) {
            $data['image'] = $image;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
            $product->update(['is_active' => false]);

            return back()->with('success', 'Produit désactivé (des commandes y sont liées).');
        }

        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ], [], ['name' => 'nom', 'category' => 'catégorie', 'price' => 'prix']) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function storeImage(Request $request): ?string
    {
        $request->validate(['image' => ['nullable', 'image', 'max:4096']]);

        return $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;
    }
}
