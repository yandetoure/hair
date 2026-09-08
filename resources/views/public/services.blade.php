@extends('layouts.public')

@section('title', 'Prestations & tarifs — '.config('salon.name'))
@section('description', 'Découvrez la carte complète du salon : coiffure, tresses, soins capillaires, manucure, pédicure et beauté du regard, avec les tarifs et les durées.')

@section('content')

    <section class="relative overflow-hidden border-b border-espresso-900/10">
        <div class="texture-gradient absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto max-w-7xl px-5 py-20 text-center lg:px-8">
            <span class="eyebrow">La carte</span>
            <h1 class="mt-4 font-display text-5xl font-semibold text-espresso-900 sm:text-6xl">Prestations & tarifs</h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-espresso-700/85">
                Des tarifs clairs, affichés à l'avance. La durée indiquée comprend le temps de préparation
                et de finition.
            </p>
        </div>
    </section>

    {{-- Navigation par univers --}}
    <div class="sticky top-[73px] z-30 border-b border-espresso-900/10 bg-cream-50/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-5 py-3 lg:px-8">
            @foreach ($categories as $category)
                <a href="#{{ $category->slug }}"
                   class="whitespace-nowrap rounded-full border border-espresso-900/15 px-5 py-2 text-sm transition hover:border-gold-500 hover:text-gold-600">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        <div class="space-y-24">
            @foreach ($categories as $category)
                <div id="{{ $category->slug }}" class="scroll-mt-40">
                    <div class="flex flex-col gap-6 border-b border-espresso-900/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                        <div class="flex items-start gap-5">
                            <x-media :src="$category->image" :alt="$category->name" :icon="$category->icon"
                                     ratio="aspect-square" class="h-20 w-20 shrink-0 rounded-2xl" />
                            <div>
                                <h2 class="font-display text-3xl font-semibold text-espresso-900 sm:text-4xl">{{ $category->name }}</h2>
                                <p class="mt-2 max-w-xl text-sm leading-relaxed text-espresso-700/75">{{ $category->description }}</p>
                            </div>
                        </div>
                        <a href="{{ route('booking.create') }}" class="btn-outline shrink-0">Réserver dans cet univers</a>
                    </div>

                    <ul class="mt-8 divide-y divide-espresso-900/8">
                        @foreach ($category->activeServices as $service)
                            <li class="group flex flex-col gap-4 py-6 sm:flex-row sm:items-center sm:justify-between" data-reveal>
                                <div class="max-w-xl">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="font-display text-2xl font-semibold text-espresso-900">
                                            <a href="{{ route('services.show', $service) }}" class="transition hover:text-gold-600">{{ $service->name }}</a>
                                        </h3>
                                        @if ($service->is_featured)
                                            <span class="rounded-full bg-gold-500/15 px-3 py-0.5 text-[10px] font-medium uppercase tracking-[0.16em] text-gold-600">Populaire</span>
                                        @endif
                                    </div>
                                    <p class="mt-1.5 text-sm leading-relaxed text-espresso-700/75">{{ $service->description }}</p>
                                    <p class="mt-2 flex items-center gap-1.5 text-xs uppercase tracking-[0.14em] text-espresso-700/55">
                                        <x-icon name="clock" class="h-3.5 w-3.5" /> {{ $service->duration_label }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2 sm:flex-col sm:items-end sm:gap-2">
                                    <span class="whitespace-nowrap font-display text-2xl font-semibold text-gold-600">{{ $service->price_label }}</span>
                                    <a href="{{ route('booking.create', ['service' => $service->slug]) }}"
                                       class="inline-flex items-center gap-1.5 whitespace-nowrap text-sm font-medium text-espresso-900 transition hover:text-gold-600">
                                        Réserver <x-icon name="arrow-right" class="h-4 w-4" />
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        {{-- Forfaits --}}
        <div class="mt-24 grid items-center gap-10 rounded-[2rem] border border-gold-500/25 bg-cream-100 p-10 lg:grid-cols-[1.2fr_1fr] lg:p-14">
            <div>
                <span class="eyebrow">Forfaits & rituels</span>
                <h2 class="mt-3 font-display text-3xl font-semibold text-espresso-900 sm:text-4xl">
                    Plusieurs prestations, un tarif plus doux
                </h2>
                <p class="mt-4 text-espresso-700/80">
                    Rituel mariée, abonnement cheveux sains, parenthèse duo : nos formules réunissent les prestations
                    qui vont bien ensemble, avec une économie à la clé.
                </p>
                <a href="{{ route('offers.index') }}" class="btn-gold mt-7">Découvrir les forfaits</a>
            </div>
            <x-media src="images/offers/abonnement.jpg" alt="Forfaits beauté" ratio="aspect-[4/3]" />
        </div>

        {{-- FAQ --}}
        <div class="mt-24">
            <x-section-title eyebrow="Questions fréquentes" title="Avant votre venue" />

            <dl class="mx-auto mt-12 max-w-3xl divide-y divide-espresso-900/10">
                @foreach ([
                    ['Faut-il venir les cheveux lavés ?', "Pour une pose de tresses, oui : cheveux propres et démêlés, lavés 48h avant. Pour une coupe ou un soin, nous nous occupons du shampooing sur place."],
                    ['Combien de temps dure une pose de braids ?', "Entre 3 et 5 heures selon la longueur et la finesse des tresses. Nous vous donnons une estimation précise à la réservation."],
                    ['Dois-je apporter mes mèches ?', "Vous pouvez apporter les vôtres ou les acheter au salon. Dans les deux cas, dites-le nous à la réservation pour caler la durée."],
                    ['Les tarifs affichés sont-ils définitifs ?', "Oui pour les prestations à prix fixe. Les fourchettes dépendent de la longueur, du volume et du temps nécessaire : le montant exact est confirmé avant de commencer."],
                    ['Puis-je venir avec un enfant ?', "Bien sûr, un coin enfants est aménagé. Nous proposons aussi des coiffures enfant sur rendez-vous."],
                    ['Comment annuler ou décaler ?', "Un appel ou un message WhatsApp au moins 24h avant suffit, sans frais."],
                ] as $i => [$question, $answer])
                    <div class="py-5" data-reveal>
                        <dt class="font-display text-xl font-semibold text-espresso-900">{{ $question }}</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-espresso-700/80">{{ $answer }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <div class="mt-24 rounded-[2rem] bg-espresso-900 p-10 text-center text-cream-100 lg:p-14">
            <h2 class="font-display text-3xl font-semibold text-gold-300 sm:text-4xl">Une envie particulière ?</h2>
            <p class="mx-auto mt-3 max-w-xl text-cream-100/75">
                Coiffures d'événement, forfaits mère-fille, abonnements entretien : parlons-en, nous construisons
                la formule qui vous correspond.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('booking.create') }}" class="btn-gold">Prendre rendez-vous</a>
                <a href="{{ route('contact') }}" class="btn inline-flex border border-cream-100/25 text-cream-100 hover:border-gold-300 hover:text-gold-300">Nous contacter</a>
            </div>
        </div>
    </section>

@endsection
