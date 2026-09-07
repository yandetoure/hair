@extends('layouts.public')

@section('title', 'Commande enregistrée — '.config('salon.name'))

@section('content')

    <section class="mx-auto max-w-3xl px-5 py-20 lg:px-8">
        <div class="card overflow-hidden">
            <div class="texture-gradient px-8 py-12 text-center">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-white text-gold-600 shadow">
                    <x-icon name="check" class="h-8 w-8" />
                </span>
                <h1 class="mt-6 font-display text-4xl font-semibold text-espresso-900">Commande enregistrée</h1>
                <p class="mt-3 text-espresso-700/85">
                    Merci {{ \Illuminate\Support\Str::before($order->customer_name, ' ') }} ! Nous préparons votre colis
                    et vous appelons pour confirmer.
                </p>
                <p class="mt-4 inline-block rounded-full bg-white/80 px-5 py-2 text-sm tracking-[0.14em] text-espresso-900">
                    Référence : <span class="font-semibold">{{ $order->reference }}</span>
                </p>
            </div>

            <div class="p-8">
                <ul class="divide-y divide-espresso-900/8 text-sm">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-4 py-3">
                            <span class="text-espresso-700/85">{{ $item->name }} <span class="text-espresso-700/50">× {{ $item->quantity }}</span></span>
                            <span class="font-medium text-espresso-900">{{ fcfa($item->line_total) }}</span>
                        </li>
                    @endforeach
                </ul>

                <dl class="mt-5 space-y-2 border-t border-espresso-900/8 pt-5 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-espresso-700/65">Sous-total</dt>
                        <dd>{{ fcfa($order->subtotal) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-espresso-700/65">{{ $order->delivery_mode === 'delivery' ? 'Livraison' : 'Retrait au salon' }}</dt>
                        <dd>{{ fcfa($order->delivery_fee) }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-espresso-900/8 pt-3">
                        <dt class="font-display text-lg text-espresso-900">Total à régler</dt>
                        <dd class="font-display text-2xl font-semibold text-gold-600">{{ fcfa($order->total) }}</dd>
                    </div>
                </dl>

                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('shop.index') }}" class="btn-dark">Continuer mes achats</a>
                    <a href="{{ route('booking.create') }}" class="btn-outline">Prendre rendez-vous</a>
                </div>
            </div>
        </div>
    </section>

@endsection
