@extends('layouts.public')

@section('title', 'Forfaits & rituels beauté — '.config('salon.name'))
@section('description', "Rituel mariée, abonnement cheveux sains, parenthèse duo : des formules beauté complètes à tarif préférentiel.")

@section('content')

    <section class="relative overflow-hidden border-b border-espresso-900/10">
        <div class="texture-gradient absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto max-w-3xl px-5 py-20 text-center lg:px-8">
            <span class="eyebrow">Forfaits & rituels</span>
            <h1 class="mt-4 font-display text-5xl font-semibold text-espresso-900 sm:text-6xl">Prendre soin, sur la durée</h1>
            <p class="mt-5 text-lg text-espresso-700/85">
                Un beau résultat tient rarement à une seule séance. Nos formules réunissent plusieurs prestations
                complémentaires, à un tarif plus doux que pris séparément.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl space-y-20 px-5 py-20 lg:px-8">
        @foreach ($offers as $index => $offer)
            <article class="grid items-center gap-12 lg:grid-cols-2" data-reveal>
                <div class="{{ $index % 2 ? 'lg:order-2' : '' }}">
                    <x-media :src="$offer->image" :alt="$offer->name" ratio="aspect-[4/3]"
                             class="rounded-[2rem] shadow-[0_40px_80px_-45px_rgba(51,34,28,0.55)]">
                        @if ($offer->saving)
                            <span class="absolute left-5 top-5 rounded-full bg-blush-500 px-4 py-1.5 text-xs font-medium text-white">
                                Économie de {{ fcfa($offer->saving) }}
                            </span>
                        @endif
                    </x-media>
                </div>

                <div class="{{ $index % 2 ? 'lg:order-1' : '' }}">
                    <span class="eyebrow">{{ $offer->tagline }}</span>
                    <h2 class="mt-3 font-display text-4xl font-semibold leading-tight text-espresso-900">{{ $offer->name }}</h2>
                    <p class="mt-4 text-lg leading-relaxed text-espresso-700/85">{{ $offer->description }}</p>

                    <ul class="mt-7 space-y-3">
                        @foreach ($offer->lines as $line)
                            <li class="flex gap-3 text-sm text-espresso-700/85">
                                <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-gold-500/15 text-gold-600">
                                    <x-icon name="check" class="h-3 w-3" />
                                </span>
                                {{ $line }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 flex flex-wrap items-end gap-8 border-t border-espresso-900/10 pt-6">
                        <div>
                            <span class="block text-xs uppercase tracking-[0.16em] text-espresso-700/55">Le forfait</span>
                            <div class="mt-1 flex items-baseline gap-3">
                                <span class="font-display text-4xl font-semibold text-gold-600">{{ fcfa($offer->price) }}</span>
                                @if ($offer->compare_price)
                                    <span class="text-sm text-espresso-700/45 line-through">{{ fcfa($offer->compare_price) }}</span>
                                @endif
                            </div>
                        </div>
                        @if ($offer->duration_label)
                            <div>
                                <span class="block text-xs uppercase tracking-[0.16em] text-espresso-700/55">Durée</span>
                                <span class="mt-1 block font-display text-2xl font-semibold text-espresso-900">{{ $offer->duration_label }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('booking.create') }}" class="btn-gold px-8 py-4">Réserver ce forfait</a>
                        <a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="btn-outline px-8 py-4">
                            <x-icon name="phone" class="h-4 w-4" /> En parler d'abord
                        </a>
                    </div>
                </div>
            </article>
        @endforeach

        <div class="rounded-[2rem] border border-gold-500/25 bg-cream-100 p-10 lg:p-14">
            <div class="grid gap-10 lg:grid-cols-[1fr_1.2fr]">
                <div>
                    <span class="eyebrow">Bon à savoir</span>
                    <h2 class="mt-3 font-display text-3xl font-semibold text-espresso-900">Comment ça marche</h2>
                    <p class="mt-3 text-sm leading-relaxed text-espresso-700/80">
                        Réservez simplement la première prestation du forfait en ligne, ou appelez-nous :
                        nous calons ensemble le calendrier complet.
                    </p>
                </div>

                <dl class="space-y-5">
                    @foreach ([
                        ['Le forfait est-il payable en une fois ?', "Non. Vous réglez à chaque séance, le tarif préférentiel s'applique dès la première."],
                        ["Puis-je l'offrir ?", 'Oui, tous nos forfaits existent en carte cadeau. Demandez-la au salon ou par WhatsApp.'],
                        ['Et si je dois décaler une séance ?', "Aucun problème, prévenez-nous 24h à l'avance et nous replanifions."],
                    ] as [$q, $a])
                        <div class="border-b border-espresso-900/10 pb-5 last:border-0 last:pb-0">
                            <dt class="font-display text-lg font-semibold text-espresso-900">{{ $q }}</dt>
                            <dd class="mt-1 text-sm leading-relaxed text-espresso-700/80">{{ $a }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>

@endsection
