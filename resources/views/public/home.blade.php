@extends('layouts.public')

@section('title', config('salon.name').' — '.config('salon.tagline').' à Abidjan')
@section('description', "Coiffure, tresses, soins capillaires, onglerie, soins du visage et beauté du regard. Réservez votre rendez-vous en ligne et commandez vos produits d'entretien.")

@section('content')

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">
        <div class="texture-gradient absolute inset-0 opacity-70"></div>
        <div class="pattern-dots absolute inset-0 opacity-40"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 pb-24 pt-16 lg:grid-cols-[1.05fr_1fr] lg:px-8 lg:pb-32 lg:pt-24">
            <div class="fade-up">
                <span class="inline-flex items-center gap-2 rounded-full border border-gold-500/40 bg-white/70 px-4 py-1.5 text-xs uppercase tracking-[0.24em] text-gold-600">
                    <x-icon name="sparkles" class="h-4 w-4" /> Depuis 2014 à Abidjan
                </span>

                <h1 class="mt-6 font-display text-5xl font-semibold leading-[1.05] text-espresso-900 sm:text-6xl lg:text-7xl">
                    L'art de sublimer<br>
                    <span class="italic text-gold-600">votre beauté</span> naturelle
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-espresso-700/85">
                    Coiffure, tresses, soins capillaires, onglerie, soins du visage et mise en beauté.
                    Sept univers réunis sous un même toit, et un rendez-vous réservé en moins d'une minute.
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

            {{-- Collage photo --}}
            <div class="relative fade-up">
                <div class="grid grid-cols-5 grid-rows-6 gap-4" style="min-height: 30rem">
                    <div class="col-span-3 row-span-6 overflow-hidden rounded-[2rem] shadow-[0_40px_80px_-40px_rgba(51,34,28,0.6)]">
                        <img src="{{ asset('images/hero/portrait.jpg') }}" alt="Coiffure tressée réalisée au salon"
                             class="h-full w-full object-cover">
                    </div>
                    <div class="col-span-2 row-span-3 overflow-hidden rounded-[1.5rem] shadow-[0_30px_60px_-40px_rgba(51,34,28,0.6)]">
                        <img src="{{ asset('images/hero/tresses.jpg') }}" alt="Réalisation de tresses collées"
                             class="h-full w-full object-cover">
                    </div>
                    <div class="col-span-2 row-span-3 overflow-hidden rounded-[1.5rem] shadow-[0_30px_60px_-40px_rgba(51,34,28,0.6)]">
                        <img src="{{ asset('images/hero/onglerie.jpg') }}" alt="Nail art réalisé au salon"
                             class="h-full w-full object-cover">
                    </div>
                </div>

                <div class="absolute -bottom-7 left-6 hidden items-center gap-3 rounded-2xl border border-espresso-900/10 bg-white/95 px-5 py-4 shadow-[0_24px_60px_-30px_rgba(51,34,28,0.45)] backdrop-blur sm:flex">
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
        <x-section-title eyebrow="Nos univers" title="Sept expertises sous un même toit"
                         subtitle="Du cheveu aux ongles, du visage au bien-être : tout ce dont vous avez besoin, réuni au même endroit." />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('services.index') }}#{{ $category->slug }}"
                   class="group relative overflow-hidden rounded-2xl transition duration-300 hover:-translate-y-1"
                   data-reveal>
                    <x-media :src="$category->image" :alt="$category->name" :icon="$category->icon"
                             ratio="aspect-[4/5]" :overlay="true" class="rounded-2xl" />
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 p-6 text-cream-100">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/15 text-gold-300 backdrop-blur">
                            <x-icon :name="$category->icon" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-display text-2xl font-semibold">{{ $category->name }}</h3>
                        <p class="mt-1 text-sm text-cream-100/80">
                            {{ $category->activeServices->count() }} prestations · dès {{ fcfa($category->activeServices->min('price') ?? 0) }}
                        </p>
                    </div>
                </a>
            @endforeach

            <div class="relative overflow-hidden rounded-2xl bg-espresso-900 p-8 text-cream-100" data-reveal>
                <div class="texture-gradient absolute inset-0 opacity-50"></div>
                <div class="relative flex h-full flex-col justify-center">
                    <h3 class="font-display text-2xl font-semibold text-gold-300">Un doute sur la prestation ?</h3>
                    <p class="mt-2 text-sm leading-relaxed text-cream-100/75">
                        Diagnostic capillaire gratuit au salon, ou un appel et on vous oriente.
                    </p>
                    <a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="btn-gold mt-6 self-start">
                        <x-icon name="phone" class="h-4 w-4" /> Nous appeler
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

    {{-- ============ RÉALISATIONS ============ --}}
    @if ($gallery->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <x-section-title align="left" eyebrow="Nos réalisations" title="Le travail parle pour nous"
                                 subtitle="Un aperçu des coiffures, poses et mises en beauté réalisées ces dernières semaines." />
                <a href="{{ route('gallery.index') }}" class="btn-outline shrink-0">Voir la galerie</a>
            </div>

            <div class="mt-12 columns-2 gap-4 lg:columns-4 [&>*]:mb-4">
                @foreach ($gallery as $item)
                    <a href="{{ route('gallery.index', ['univers' => $item->category]) }}"
                       class="group block break-inside-avoid overflow-hidden rounded-2xl" data-reveal>
                        <x-media :src="$item->image" :alt="$item->title" :overlay="true"
                                 :ratio="$item->format === 'portrait' ? 'aspect-[4/5]' : 'aspect-[4/3]'">
                            <div class="pointer-events-none absolute inset-x-0 bottom-0 p-4">
                                <span class="text-[10px] uppercase tracking-[0.2em] text-gold-300">{{ $item->category }}</span>
                                <p class="font-display text-lg font-semibold leading-snug text-cream-100">{{ $item->title }}</p>
                            </div>
                        </x-media>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ L'EXPÉRIENCE EN SALON ============ --}}
    <section class="bg-cream-100 py-24">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <x-section-title eyebrow="L'expérience" title="Une parenthèse, pas une chaise"
                             subtitle="Lumière douce, thé offert, fauteuils confortables : on prend le temps qu'il faut." />

            <div class="mt-14 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['images/salon/accueil.jpg', "L'accueil", "On vous installe, on écoute votre routine et vos envies avant de toucher un cheveu."],
                    ['images/salon/postes.jpg', 'Le coiffage', 'Trois postes, jamais plus : pas de file d’attente, pas de prestation bâclée.'],
                    ['images/salon/bar-a-soins.jpg', 'Le bar à soins', 'Les produits utilisés en cabine sont expliqués, testés, puis disponibles en boutique.'],
                ] as $i => [$image, $title, $text])
                    <figure data-reveal>
                        <x-media :src="$image" :alt="$title" ratio="aspect-[4/3]" class="shadow-[0_30px_60px_-40px_rgba(51,34,28,0.5)]" />
                        <figcaption class="mt-5">
                            <span class="eyebrow">0{{ $i + 1 }}</span>
                            <h3 class="mt-1 font-display text-2xl font-semibold text-espresso-900">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-espresso-700/75">{{ $text }}</p>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FORFAITS ============ --}}
    @if ($offers->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <x-section-title align="left" eyebrow="Forfaits & rituels" title="Des formules pensées pour durer"
                                 subtitle="Plusieurs prestations réunies à tarif préférentiel, pour un événement ou un suivi dans le temps." />
                <a href="{{ route('offers.index') }}" class="btn-outline shrink-0">Tous les forfaits</a>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-3">
                @foreach ($offers as $offer)
                    <article class="card group flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1" data-reveal>
                        <x-media :src="$offer->image" :alt="$offer->name" ratio="aspect-[3/2]" class="rounded-none rounded-t-2xl">
                            @if ($offer->saving)
                                <span class="absolute right-4 top-4 rounded-full bg-blush-500 px-3 py-1 text-xs font-medium text-white">
                                    −{{ fcfa($offer->saving) }}
                                </span>
                            @endif
                        </x-media>

                        <div class="flex flex-1 flex-col p-6">
                            <span class="eyebrow">{{ $offer->tagline }}</span>
                            <h3 class="mt-2 font-display text-2xl font-semibold text-espresso-900">{{ $offer->name }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-espresso-700/80">{{ $offer->description }}</p>

                            <ul class="mt-5 space-y-2 text-sm text-espresso-700/85">
                                @foreach ($offer->lines->take(3) as $line)
                                    <li class="flex gap-2.5">
                                        <span class="mt-1 grid h-4 w-4 shrink-0 place-items-center rounded-full bg-gold-500/15 text-gold-600">
                                            <x-icon name="check" class="h-2.5 w-2.5" />
                                        </span>
                                        {{ $line }}
                                    </li>
                                @endforeach
                                @if ($offer->lines->count() > 3)
                                    <li class="pl-6 text-xs text-gold-600">+ {{ $offer->lines->count() - 3 }} autres prestations incluses</li>
                                @endif
                            </ul>

                            <div class="mt-6 flex items-end justify-between border-t border-espresso-900/10 pt-5">
                                <div>
                                    @if ($offer->compare_price)
                                        <span class="block text-xs text-espresso-700/45 line-through">{{ fcfa($offer->compare_price) }}</span>
                                    @endif
                                    <span class="font-display text-2xl font-semibold text-gold-600">{{ fcfa($offer->price) }}</span>
                                </div>
                                <a href="{{ route('booking.create') }}" class="btn-dark py-2.5 text-xs">Réserver</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ LE SALON ============ --}}
    <section class="bg-white py-24">
        <div class="mx-auto grid max-w-7xl items-center gap-14 px-5 lg:grid-cols-2 lg:px-8">
            <div class="relative" data-reveal>
                <x-media src="images/salon/coiffage.jpg" alt="Coiffage au salon" ratio="aspect-[4/5]"
                         class="rounded-[2rem] shadow-[0_40px_80px_-45px_rgba(51,34,28,0.6)]" />
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
                    <div class="group text-center" data-reveal>
                        @if ($member->photo)
                            <x-media :src="$member->photo" :alt="$member->name" ratio="aspect-[3/4]" :overlay="true">
                                <div class="pointer-events-none absolute inset-x-0 bottom-0 p-5 text-left">
                                    <h3 class="font-display text-xl font-semibold text-cream-100">{{ $member->name }}</h3>
                                    <p class="text-xs uppercase tracking-[0.16em] text-gold-300">{{ $member->role }}</p>
                                </div>
                            </x-media>
                        @else
                            <div class="aspect-[3/4] overflow-hidden rounded-2xl bg-espresso-900">
                                <div class="texture-gradient grid h-full w-full place-items-center">
                                    <span class="font-display text-5xl font-semibold text-gold-300">{{ $member->initials }}</span>
                                </div>
                            </div>
                            <h3 class="mt-5 font-display text-xl font-semibold text-espresso-900">{{ $member->name }}</h3>
                            <p class="text-xs uppercase tracking-[0.16em] text-gold-600">{{ $member->role }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ TÉMOIGNAGES ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="relative overflow-hidden bg-espresso-900 py-24 text-cream-100">
            <div class="texture-gradient absolute inset-0 opacity-40"></div>
            <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <span class="eyebrow text-gold-300">Elles en parlent</span>
                    <h2 class="mt-3 font-display text-4xl font-semibold sm:text-5xl">La confiance, notre plus belle vitrine</h2>
                </div>

                <div class="mt-14 grid gap-6 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="rounded-2xl border border-cream-100/15 bg-white/5 p-8 backdrop-blur-sm" data-reveal>
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

    {{-- ============ JOURNAL ============ --}}
    @if ($posts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <x-section-title align="left" eyebrow="Journal beauté" title="Nos conseils, sans blabla"
                                 subtitle="Ce que nous répétons en cabine, écrit noir sur blanc pour vos routines à la maison." />
                <a href="{{ route('journal.index') }}" class="btn-outline shrink-0">Tous les articles</a>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <article class="group" data-reveal>
                        <a href="{{ route('journal.show', $post) }}">
                            <x-media :src="$post->image" :alt="$post->title" ratio="aspect-[5/3]" />
                        </a>
                        <div class="mt-5">
                            <div class="flex items-center gap-3 text-xs uppercase tracking-[0.16em] text-gold-600">
                                <span>{{ $post->category }}</span>
                                <span class="text-espresso-700/40">· {{ $post->reading_time }} min de lecture</span>
                            </div>
                            <h3 class="mt-2 font-display text-2xl font-semibold leading-snug text-espresso-900 transition group-hover:text-gold-600">
                                <a href="{{ route('journal.show', $post) }}">{{ $post->title }}</a>
                            </h3>
                            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-espresso-700/80">{{ $post->excerpt }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ CTA ============ --}}
    <section class="mx-auto max-w-7xl px-5 pb-24 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem]">
            <img src="{{ asset('images/salon/equipe-au-travail.jpg') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-espresso-900/80"></div>
            <div class="relative px-8 py-20 text-center text-cream-100 lg:px-16">
                <span class="eyebrow text-gold-300">Votre prochain rendez-vous</span>
                <h2 class="mt-4 font-display text-4xl font-semibold sm:text-5xl">
                    Offrez-vous un moment rien qu'à vous
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-cream-100/80">
                    Choisissez votre prestation, votre jour et votre heure. Nous confirmons votre créneau par téléphone.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('booking.create') }}" class="btn-gold px-8 py-4">Réserver maintenant</a>
                    <a href="{{ route('contact') }}" class="btn border border-cream-100/30 px-8 py-4 text-cream-100 hover:border-gold-300 hover:text-gold-300">Nous trouver</a>
                </div>
            </div>
        </div>
    </section>

@endsection
