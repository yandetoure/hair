@extends('layouts.public')

@section('title', $product->name.' — Boutique '.config('salon.name'))
@section('description', $product->description)

@section('content')

    <section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
        <nav class="flex items-center gap-2 text-xs uppercase tracking-[0.16em] text-espresso-700/50">
            <a href="{{ route('shop.index') }}" class="hover:text-gold-600">Boutique</a>
            <span>/</span>
            <span class="text-gold-600">{{ $product->category }}</span>
        </nav>

        <div class="mt-10 grid gap-14 lg:grid-cols-2">
            <x-media :src="$product->image" :alt="$product->name" icon="box" ratio="aspect-square"
                     class="shadow-[0_30px_70px_-40px_rgba(51,34,28,0.6)]" />

            <div>
                <span class="eyebrow">{{ $product->category }}</span>
                <h1 class="mt-3 font-display text-5xl font-semibold leading-tight text-espresso-900">{{ $product->name }}</h1>
                <p class="mt-4 font-display text-3xl font-semibold text-gold-600">{{ fcfa($product->price) }}</p>
                <p class="mt-5 text-lg leading-relaxed text-espresso-700/85">{{ $product->description }}</p>

                <p class="mt-6 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm {{ $product->in_stock ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $product->in_stock ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                    {{ $product->in_stock ? 'Disponible — '.$product->stock.' en stock' : 'Rupture de stock' }}
                </p>

                @if ($product->in_stock)
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-8 flex flex-wrap items-center gap-4">
                        @csrf
                        <div class="flex items-center gap-3 rounded-full border border-espresso-900/15 px-4 py-2">
                            <label for="quantity" class="text-xs uppercase tracking-[0.14em] text-espresso-700/55">Qté</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-14 border-0 bg-transparent text-center text-sm outline-none">
                        </div>
                        <button class="btn-gold px-8 py-4">
                            <x-icon name="bag" class="h-4 w-4" /> Ajouter au panier
                        </button>
                    </form>
                @endif

                <div class="mt-10 space-y-3 border-t border-espresso-900/10 pt-8 text-sm text-espresso-700/80">
                    @foreach ([
                        ['box', 'Retrait gratuit au salon, du lundi au samedi'],
                        ['map-pin', 'Livraison Abidjan : '.fcfa(config('salon.delivery_fee')).' — offerte dès '.fcfa(config('salon.free_delivery_from'))],
                        ['wallet', 'Paiement à la réception (espèces ou mobile money)'],
                    ] as [$icon, $text])
                        <p class="flex items-center gap-3">
                            <x-icon :name="$icon" class="h-4 w-4 text-gold-600" /> {{ $text }}
                        </p>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-24">
                <x-section-title align="left" eyebrow="Complétez votre routine" title="Souvent achetés ensemble" />
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>

@endsection
