@extends('layouts.admin')

@section('title', $product->exists ? 'Modifier un produit' : 'Nouveau produit')
@section('heading', $product->exists ? 'Modifier le produit' : 'Nouveau produit')
@section('subheading', $product->exists ? $product->name : 'Ajoutez une référence à la boutique')

@section('content')

<form method="POST" enctype="multipart/form-data"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      class="grid max-w-4xl gap-6">
    @csrf
    @if ($product->exists) @method('PUT') @endif

    <div class="card p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="name">Nom du produit *</label>
                <input type="text" name="name" id="name" class="field" required value="{{ old('name', $product->name) }}">
            </div>

            <div>
                <label class="label" for="category">Catégorie *</label>
                <input type="text" name="category" id="category" class="field" required list="categories"
                       value="{{ old('category', $product->category) }}">
                <datalist id="categories">
                    @foreach (\App\Models\Product::distinct()->pluck('category') as $category)
                        <option value="{{ $category }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="label" for="price">Prix ({{ config('salon.currency') }}) *</label>
                <input type="number" name="price" id="price" class="field" required min="0" value="{{ old('price', $product->price) }}">
            </div>

            <div>
                <label class="label" for="stock">Stock disponible *</label>
                <input type="number" name="stock" id="stock" class="field" required min="0" value="{{ old('stock', $product->stock) }}">
            </div>

            <div>
                <label class="label" for="image">Photo</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full rounded-xl border border-espresso-900/15 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-espresso-900 file:px-3 file:py-1.5 file:text-xs file:text-cream-100">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="" class="mt-3 h-24 w-24 rounded-lg object-cover">
                @endif
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="field">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex flex-wrap gap-6 sm:col-span-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" class="rounded accent-[#c19a3c]"
                           @checked(old('is_active', $product->is_active ?? true))> En vente sur le site
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" class="rounded accent-[#c19a3c]"
                           @checked(old('is_featured', $product->is_featured))> Mettre en avant
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $product->exists ? 'Enregistrer' : 'Créer le produit' }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
