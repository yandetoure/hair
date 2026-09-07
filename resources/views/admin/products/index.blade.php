@extends('layouts.admin')

@section('title', 'Boutique')
@section('heading', 'Produits & accessoires')
@section('subheading', $products->total().' références en boutique')

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="btn-gold py-2.5 text-xs">
        <x-icon name="plus" class="h-4 w-4" /> Nouveau produit
    </a>
@endsection

@section('content')

<form method="GET" class="card mb-6 p-4">
    <div class="relative max-w-md">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit…" class="field pl-10">
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-espresso-700/40" />
    </div>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 bg-cream-50 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="px-5 py-3">Produit</th>
                    <th class="px-5 py-3">Catégorie</th>
                    <th class="px-5 py-3 text-right">Prix</th>
                    <th class="px-5 py-3 text-right">Stock</th>
                    <th class="px-5 py-3">État</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @forelse ($products as $product)
                    <tr class="transition hover:bg-cream-50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-cream-200 text-espresso-700/40">
                                        <x-icon name="box" class="h-4 w-4" />
                                    </span>
                                @endif
                                <div>
                                    <span class="block font-medium text-espresso-900">{{ $product->name }}</span>
                                    <span class="block max-w-sm truncate text-xs text-espresso-700/55">{{ $product->description }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-espresso-700/75">{{ $product->category }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right font-medium text-gold-600">{{ fcfa($product->price) }}</td>
                        <td class="px-5 py-4 text-right">
                            <span class="rounded-full px-2.5 py-0.5 text-xs {{ $product->stock > 3 ? 'bg-emerald-50 text-emerald-700' : ($product->stock > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-600') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] ring-1 {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                                    {{ $product->is_active ? 'En vente' : 'Masqué' }}
                                </span>
                                @if ($product->is_featured)
                                    <span class="rounded-full bg-gold-500/15 px-2.5 py-0.5 text-[11px] text-gold-600 ring-1 ring-gold-300">Mis en avant</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
                                    <x-icon name="edit" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf @method('DELETE')
                                    <button class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 text-rose-500 transition hover:border-rose-300">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-16 text-center text-espresso-700/50">Aucun produit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $products->links() }}</div>

@endsection
