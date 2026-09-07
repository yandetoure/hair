@extends('layouts.public')

@section('title', config('salon.name').' — '.config('salon.tagline').' à Abidjan')
@section('description', "Coiffure, tresses, soins capillaires, manucure et pédicure. Réservez votre rendez-vous en ligne et commandez vos produits d'entretien.")

@section('content')

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">
        <div class="texture-gradient absolute inset-0 opacity-70"></div>
        <div class="pattern-dots absolute inset-0 opacity-40"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 pb-24 pt-16 lg:grid-cols-2 lg:px-8 lg:pb-32 lg:pt-24">
            <div class="fade-up">
                <span class="inline-flex items-center gap-2 rounded-full border border-gold-500/40 bg-white/70 px-4 py-1.5 text-xs uppercase tracking-[0.24em] text-gold-600">
                    <x-icon name="sparkles" class="h-4 w-4" /> Depuis 2014 à Abidjan
                </span>

                <h1 class="mt-6 font-display text-5xl font-semibold leading-[1.05] text-espresso-900 sm:text-6xl lg:text-7xl">
                    L'art de sublimer<br>
                    <span class="italic text-gold-600">votre beauté</span> naturelle
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-espresso-700/85">
                    Coiffure, tresses, soins capillaires, onglerie et beauté du regard.
                    Une équipe passionnée, des produits sélectionnés, et un rendez-vous réservé
                    en moins d'une minute.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-4">
                    <a href="{{ route('booking.create') }}" class="btn-gold px-8 py-4">
                        Prendre rendez-vous <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                    <a href="{{ route('services.index') }}" class="btn-outline px-8 py-4">Voir les tarifs</a>
                </div>

                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-6 border-t border-espresso-900/10 pt-8">
                    @foreach ([
                        ['12 ans', "d'expérience"],
                        ['4 800+', 'clientes conseillées'],
                        ['4,9/5', 'note moyenne'],
                    ] as [$value, $label])
                        <div>
                            <dt class="font-display text-3xl font-semibold text-espresso-900">{{ $value }}</dt>
                            <dd class="mt-1 text-xs uppercase tracking-[0.14em] text-espresso-700/60">{{ $label }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="relative fade-up">
                <div class="relative overflow-hidden rounded-[2.5rem] border border-white/60 shadow-[0_40px_80px_-40px_rgba(51,34,28,0.6)]">
                    <div class="aspect-[4/5] bg-gradient-to-br from-espresso-800 via-espresso-900 to-espresso-700">
                        <div class="texture-gradient absolute inset-0 opacity-80 mix-blend-soft-light"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8 text-cream-100">
                            <x-icon name="ciseaux" class="mb-6 h-14 w-14 text-gold-300" />
                            <p class="font-display text-3xl leading-snug">
                                « Chaque cheveu raconte une histoire.<br>Nous prenons le temps de l'écouter. »
                            </p>
                            <span class="mt-4 text-xs uppercase tracking-[0.24em] text-gold-300">Awa Koné — Fondatrice</span>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-8 -left-6 hidden w-64 rounded-2xl border border-espresso-900/10 bg-white p-5 shadow-[0_24px_60px_-30px_rgba(51,34,28,0.45)] sm:block">
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-full bg-gold-500/15 text-gold-600">
                            <x-icon name="clock" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-sm font-medium text-espresso-900">Ouvert aujourd'hui</p>
                            @php $today = config('salon.hours')[now()->isoWeekday()] ?? null; @endphp
                            <p class="text-xs text-espresso-700/60">{{ $today ? $today[0].' – '.$today[1] : 'Fermé — à demain !' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ ATOUTS ============ --}}
    <section class="border-y border-espresso-900/10 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 py-12 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
            @foreach ([
                ['calendar', 'Réservation en ligne', 'Choisissez votre créneau 24h/24, confirmation immédiate.'],
                ['soins', 'Soins sur mesure', 'Diagnostic capillaire offert avant chaque première prestation.'],
                ['sparkles', 'Hygiène irréprochable', 'Matériel stérilisé et espace onglerie dédié.'],
                ['box', 'Produits à emporter', 'Les soins utilisés en salon, disponibles en boutique.'],
            ] as [$icon, $title, $text])
                <div class="flex gap-4" data-reveal>
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-cream-100 text-gold-600">
                        <x-icon :name="$icon" class="h-5 w-5" />
                    </span>
                    <div>
                        <h3 class="font-display text-xl font-semibold text-espresso-900">{{ $title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-espresso-700/75">{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ UNIVERS ============ --}}
    <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <x-section-title eyebrow="Nos univers" title="Tout ce dont vos cheveux ont besoin"
                         subtitle="Cinq pôles d'expertise réunis sous un même toit, pour un rendez-vous beauté complet." />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ route('services.index') }}#{{ $category->slug }}"
                   class="card group relative overflow-hidden p-8 transition duration-300 hover:-translate-y-1 hover:border-gold-500/40"
                   data-reveal>
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-espresso-900 text-gold-300 transition group-hover:bg-gold-500 group-hover:text-white">
                        <x-icon :name="$category->icon" class="h-6 w-6" />
                    </span>
                    <h3 class="mt-6 font-display text-2xl font-semibold text-espresso-900">{{ $category->name }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-espresso-700/75">{{ $category->description }}</p>
                    <p class="mt-5 flex items-center gap-2 text-sm font-medium text-gold-600">
                        {{ $category->activeServices->count() }} prestations
                        <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
                    </p>
                </a>
            @endforeach

            <div class="relative overflow-hidden rounded-2xl bg-espresso-900 p-8 text-cream-100" data-reveal>
                <div class="texture-gradient absolute inset-0 opacity-50"></div>
                <div class="relative">
                    <h3 class="font-display text-2xl font-semibold text-gold-300">Un doute sur la prestation ?</h3>
                    <p class="mt-2 text-sm leading-relaxed text-cream-100/75">
                        Passez nous voir pour un diagnostic capillaire gratuit, ou appelez-nous : on vous oriente.
                    </p>
                    <a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="btn-gold mt-6">
                        <x-icon name="phone" class="h-4 w-4" /> {{ config('salon.phone') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PRESTATIONS PHARES ============ --}}
    <section class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <x-section-title align="left" eyebrow="Les incontournables" title="Prestations phares"
                                 subtitle="Les rendez-vous préférés de nos clientes, réservables en ligne." />
                <a href="{{ route('services.index') }}" class="btn-outline shrink-0">Toute la carte</a>
            </div>

            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredServices as $service)
                    <div data-reveal>
                        <x-service-card :service="$service" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ LE SALON ============ --}}
    <section class="mx-auto grid max-w-7xl items-center gap-14 px-5 py-24 lg:grid-cols-2 lg:px-8">
        <div class="relative" data-reveal>
            <div class="aspect-[4/5] overflow-hidden rounded-[2rem] bg-cream-200">
                <div class="texture-gradient h-full w-full"></div>
            </div>
            <div class="absolute -right-4 bottom-8 hidden rounded-2xl bg-white p-6 shadow-[0_24px_60px_-30px_rgba(51,34,28,0.45)] sm:block">
                <p class="font-display text-4xl font-semibold text-gold-600">3</p>
                <p class="text-xs uppercase tracking-[0.16em] text-espresso-700/60">postes de coiffage</p>
            </div>
        </div>

        <div data-reveal>
            <x-section-title align="left" eyebrow="Le salon" title="Un écrin chaleureux au cœur d'Abidjan"
                             subtitle="Nous avons pensé chaque détail pour que votre rendez-vous soit une parenthèse : lumière douce, fauteuils confortables, thé offert et une équipe qui écoute avant de conseiller." />

            <ul class="mt-8 space-y-4">
                @foreach ([
                    'Diagnostic capillaire personnalisé à chaque première visite',
                    'Produits adaptés aux cheveux crépus, bouclés et lissés',
                    'Espace onglerie séparé, matériel stérilisé après chaque cliente',
                    'Coin enfants et accès facile depuis Cocody',
                ] as $point)
                    <li class="flex gap-3 text-sm text-espresso-700/85">
                        <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-gold-500/15 text-gold-600">
                            <x-icon name="check" class="h-3 w-3" />
                        </span>
                        {{ $point }}
                    </li>
                @endforeach
            </ul>

            <div class="mt-9 flex flex-wrap gap-4">
                <a href="{{ route('salon') }}" class="btn-dark">Découvrir le salon</a>
                <a href="{{ route('booking.create') }}" class="btn-outline">Réserver ma place</a>
            </div>
        </div>
    </section>

    {{-- ============ BOUTIQUE ============ --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="bg-cream-100 py-24">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <x-section-title align="left" eyebrow="Boutique" title="Prolongez le soin à la maison"
                                     subtitle="Huiles, masques, accessoires satin : retrait au salon ou livraison à Abidjan." />
                    <a href="{{ route('shop.index') }}" class="btn-outline shrink-0">Voir la boutique</a>
                </div>

                <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($featuredProducts as $product)
                        <div data-reveal>
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ ÉQUIPE ============ --}}
    @if ($staff->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <x-section-title eyebrow="L'équipe" title="Des mains expertes, une écoute sincère" />

            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($staff as $member)
                    <div class="text-center" data-reveal>
                        @if ($member->photo)
                            <x-media :src="$member->photo" :alt="$member->name" ratio="aspect-[3/4]" />
                        @else
                            <div class="aspect-[3/4] overflow-hidden rounded-2xl bg-espresso-900">
                                <div class="texture-gradient grid h-full w-full place-items-center">
                                    <span class="font-display text-5xl font-semibold text-gold-300">{{ $member->initials }}</span>
                                </div>
                            </div>
                        @endif
                        <h3 class="mt-5 font-display text-xl font-semibold text-espresso-900">{{ $member->name }}</h3>
                        <p class="text-xs uppercase tracking-[0.16em] text-gold-600">{{ $member->role }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ TÉMOIGNAGES ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-espresso-900 py-24 text-cream-100">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <span class="eyebrow text-gold-300">Elles en parlent</span>
                    <h2 class="mt-3 font-display text-4xl font-semibold sm:text-5xl">La confiance, notre plus belle vitrine</h2>
                </div>

                <div class="mt-14 grid gap-6 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="rounded-2xl border border-cream-100/15 bg-white/5 p-8" data-reveal>
                            <div class="flex gap-1 text-gold-300">
                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                    <x-icon name="star" class="h-4 w-4 fill-current" />
                                @endfor
                            </div>
                            <blockquote class="mt-5 font-display text-xl leading-relaxed">« {{ $testimonial->content }} »</blockquote>
                            <figcaption class="mt-6 text-sm text-cream-100/60">
                                <span class="font-medium text-cream-100">{{ $testimonial->author }}</span>
                                @if ($testimonial->context) — {{ $testimonial->context }} @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CTA ============ --}}
    <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem] border border-gold-500/25 bg-cream-100 p-10 text-center lg:p-16">
            <div class="pattern-dots absolute inset-0 opacity-50"></div>
            <div class="relative">
                <span class="eyebrow">Votre prochain rendez-vous</span>
                <h2 class="mt-4 font-display text-4xl font-semibold text-espresso-900 sm:text-5xl">
                    Offrez-vous un moment rien qu'à vous
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-espresso-700/80">
                    Choisissez votre prestation, votre jour et votre heure. Nous confirmons votre créneau par téléphone.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('booking.create') }}" class="btn-gold px-8 py-4">Réserver maintenant</a>
                    <a href="{{ route('contact') }}" class="btn-outline px-8 py-4">Nous trouver</a>
                </div>
            </div>
        </div>
    </section>

@endsection
