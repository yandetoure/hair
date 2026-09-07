@extends('layouts.public')

@section('title', 'Mon panier — '.config('salon.name'))

@section('content')

    <section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
        <h1 class="font-display text-5xl font-semibold text-espresso-900">Mon panier</h1>

        @if ($items->isEmpty())
            <div class="mt-12 rounded-2xl border border-dashed border-espresso-900/15 py-20 text-center">
                <x-icon name="bag" class="mx-auto h-10 w-10 text-espresso-700/30" />
                <p class="mt-4 text-espresso-700/70">Votre panier est vide pour le moment.</p>
                <a href="{{ route('shop.index') }}" class="btn-gold mt-6">Découvrir la boutique</a>
            </div>
        @else
            <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_360px]">
                <div class="card divide-y divide-espresso-900/8">
                    @foreach ($items as $item)
                        @php $product = $item['product']; @endphp
                        <div class="flex flex-col gap-5 p-6 sm:flex-row sm:items-center">
                            <x-media :src="$product->image" :alt="$product->name" icon="box" ratio="aspect-square"
                                     class="w-24 shrink-0" />

                            <div class="flex-1">
                                <span class="eyebrow">{{ $product->category }}</span>
                                <h2 class="mt-1 font-display text-xl font-semibold text-espresso-900">
                                    <a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a>
                                </h2>
                                <p class="mt-1 text-sm text-espresso-700/65">{{ fcfa($product->price) }} l'unité</p>
                            </div>

                            <form method="POST" action="{{ route('cart.update', $product) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ max(1, $product->stock) }}"
                                       class="w-20 rounded-lg border border-espresso-900/15 px-3 py-2 text-center text-sm">
                                <button class="rounded-lg border border-espresso-900/15 px-3 py-2 text-xs uppercase tracking-[0.12em] transition hover:border-gold-500 hover:text-gold-600">
                                    OK
                                </button>
                            </form>

                            <div class="text-right sm:w-32">
                                <p class="font-display text-xl font-semibold text-espresso-900">{{ fcfa($item['line_total']) }}</p>
                                <form method="POST" action="{{ route('cart.remove', $product) }}" class="mt-1">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 text-xs text-rose-500 hover:text-rose-600">
                                        <x-icon name="trash" class="h-3.5 w-3.5" /> Retirer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="lg:sticky lg:top-28 lg:self-start">
                    <div class="card p-6">
                        <h2 class="font-display text-2xl font-semibold text-espresso-900">Récapitulatif</h2>

                        <dl class="mt-6 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-espresso-700/65">Sous-total</dt>
                                <dd class="font-medium text-espresso-900">{{ fcfa($subtotal) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-espresso-700/65">Livraison</dt>
                                <dd class="text-espresso-700/65">calculée à l'étape suivante</dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex items-center justify-between border-t border-espresso-900/8 pt-5">
                            <span class="font-display text-lg text-espresso-900">Total</span>
                            <span class="font-display text-2xl font-semibold text-gold-600">{{ fcfa($subtotal) }}</span>
                        </div>

                        <a href="{{ route('checkout.create') }}" class="btn-gold mt-6 w-full py-4">Valider ma commande</a>
                        <a href="{{ route('shop.index') }}" class="btn-outline mt-3 w-full">Continuer mes achats</a>
                    </div>

                    <p class="mt-4 text-center text-xs leading-relaxed text-espresso-700/55">
                        Livraison offerte à Abidjan dès {{ fcfa(config('salon.free_delivery_from')) }} d'achat.
                    </p>
                </aside>
            </div>
        @endif
    </section>

@endsection
