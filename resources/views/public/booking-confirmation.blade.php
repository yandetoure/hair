@extends('layouts.public')

@section('title', 'Rendez-vous confirmé — '.config('salon.name'))

@section('content')

    <section class="mx-auto max-w-3xl px-5 py-20 lg:px-8">
        <div class="card overflow-hidden text-center">
            <div class="texture-gradient px-8 py-12">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-white text-gold-600 shadow">
                    <x-icon name="check" class="h-8 w-8" />
                </span>
                <h1 class="mt-6 font-display text-4xl font-semibold text-espresso-900">Merci {{ \Illuminate\Support\Str::before($appointment->customer_name, ' ') }} !</h1>
                <p class="mt-3 text-espresso-700/85">
                    Votre demande est enregistrée. Nous vous appelons rapidement pour confirmer le créneau.
                </p>
                <p class="mt-4 inline-block rounded-full bg-white/80 px-5 py-2 text-sm tracking-[0.14em] text-espresso-900">
                    Référence : <span class="font-semibold">{{ $appointment->reference }}</span>
                </p>
            </div>

            <dl class="grid gap-px bg-espresso-900/8 text-left sm:grid-cols-2">
                @foreach ([
                    ['Prestation', $appointment->service->name],
                    ['Praticienne', $appointment->staff?->name ?? 'Première disponible'],
                    ['Date', fr_date($appointment->starts_at)],
                    ['Heure', $appointment->starts_at->format('H:i').' → '.$appointment->ends_at->format('H:i')],
                    ['Tarif', fcfa($appointment->price)],
                    ['Statut', $appointment->status_label],
                ] as [$label, $value])
                    <div class="bg-white p-6">
                        <dt class="text-xs uppercase tracking-[0.16em] text-espresso-700/55">{{ $label }}</dt>
                        <dd class="mt-1 font-display text-xl font-semibold text-espresso-900">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="border-t border-espresso-900/8 p-8">
                <p class="text-sm text-espresso-700/70">
                    Un imprévu ? Prévenez-nous au moins 24h à l'avance au
                    <a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="text-gold-600">{{ config('salon.phone') }}</a>.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" class="btn-dark">Retour à l'accueil</a>
                    <a href="{{ route('shop.index') }}" class="btn-outline">Découvrir la boutique</a>
                </div>
            </div>
        </div>
    </section>

@endsection
