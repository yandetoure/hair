@extends('layouts.public')

@section('title', 'Boutique — '.config('salon.name'))
@section('description', "Produits de soin, accessoires satin et coffrets : commandez en ligne, retirez au salon ou faites-vous livrer à Abidjan.")

@section('content')

    <section class="relative overflow-hidden border-b border-espresso-900/10">
        <div class="texture-gradient absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto max-w-7xl px-5 py-20 text-center lg:px-8">
            <span class="eyebrow">Boutique</span>
            <h1 class="mt-4 font-display text-5xl font-semibold text-espresso-900 sm:text-6xl">Le soin continue chez vous</h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-espresso-700/85">
                Les produits et accessoires que nous utilisons en cabine, sélectionnés pour les cheveux texturés.
                Retrait gratuit au salon — livraison à Abidjan offerte dès {{ fcfa(config('salon.free_delivery_from')) }}.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-14 lg:px-8">
        <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('shop.index') }}"
                   class="rounded-full border px-5 py-2 text-sm transition {{ request('categorie') ? 'border-espresso-900/15 hover:border-gold-500' : 'border-gold-500 bg-gold-500/10 text-gold-600' }}">
                    Tout
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('shop.index', ['categorie' => $category]) }}"
                       class="rounded-full border px-5 py-2 text-sm transition {{ request('categorie') === $category ? 'border-gold-500 bg-gold-500/10 text-gold-600' : 'border-espresso-900/15 hover:border-gold-500' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>

            <div class="relative sm:w-72">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit…"
                       class="field pl-11">
                <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-espresso-700/40" />
                @if (request('categorie'))
                    <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                @endif
            </div>
        </form>

        @if ($products->isEmpty())
            <p class="mt-16 rounded-2xl border border-dashed border-espresso-900/15 py-16 text-center text-espresso-700/60">
                Aucun produit ne correspond à votre recherche.
            </p>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <div data-reveal>
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif
    </section>

@endsection
