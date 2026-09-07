@extends('layouts.public')

@section('title', 'Le salon — '.config('salon.name'))
@section('description', "Découvrez l'histoire, l'équipe et l'esprit de ".config('salon.name').', salon de coiffure et de beauté à Abidjan.')

@section('content')

    <section class="relative overflow-hidden">
        <div class="texture-gradient absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto max-w-4xl px-5 py-24 text-center lg:px-8">
            <span class="eyebrow">Notre maison</span>
            <h1 class="mt-4 font-display text-5xl font-semibold leading-tight text-espresso-900 sm:text-6xl">
                Un salon né d'une conviction :<br><span class="italic text-gold-600">prendre le temps</span>
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-espresso-700/85">
                Depuis 2014, {{ config('salon.name') }} accompagne les femmes d'Abidjan dans la santé et la beauté
                de leurs cheveux. Pas de gestes pressés, pas de promesses vides : un diagnostic, un conseil,
                et un résultat qui vous ressemble.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 pb-24 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-3">
            @foreach ([
                ['aspect-[4/5]', 'Espace coiffage'],
                ['aspect-[4/5] sm:mt-10', 'Bar à soins'],
                ['aspect-[4/5]', 'Espace onglerie'],
            ] as [$ratio, $label])
                <figure data-reveal>
                    <div class="{{ $ratio }} overflow-hidden rounded-[1.75rem] bg-cream-200">
                        <div class="texture-gradient pattern-dots h-full w-full"></div>
                    </div>
                    <figcaption class="mt-3 text-center text-xs uppercase tracking-[0.2em] text-espresso-700/55">{{ $label }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>

    <section class="border-y border-espresso-900/10 bg-white py-24">
        <div class="mx-auto grid max-w-7xl gap-14 px-5 lg:grid-cols-2 lg:px-8">
            <div>
                <x-section-title align="left" eyebrow="Notre approche" title="Le cheveu d'abord, la coiffure ensuite" />
                <div class="mt-8 space-y-6 text-sm leading-relaxed text-espresso-700/85">
                    <p>
                        Chaque prestation démarre par un échange : votre routine, vos habitudes, ce qui a fonctionné
                        et ce qui a abîmé. Nous adaptons ensuite les produits, la tension des tresses et la fréquence
                        des soins à votre réalité, pas à un catalogue.
                    </p>
                    <p>
                        Nous privilégions des marques respectueuses du cuir chevelu, et nous vous expliquons comment
                        reproduire les gestes à la maison. Les produits utilisés en cabine sont disponibles à la boutique,
                        avec le mode d'emploi.
                    </p>
                </div>

                <dl class="mt-10 grid grid-cols-3 gap-6 border-t border-espresso-900/10 pt-8">
                    @foreach ([['2014', 'ouverture'], ['4', 'expertes'], ['4,9/5', 'satisfaction']] as [$v, $l])
                        <div>
                            <dt class="font-display text-3xl font-semibold text-espresso-900">{{ $v }}</dt>
                            <dd class="mt-1 text-xs uppercase tracking-[0.14em] text-espresso-700/60">{{ $l }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="space-y-4">
                @foreach ([
                    ['1', 'Le diagnostic', "On observe la fibre, le cuir chevelu et l'historique de vos soins."],
                    ['2', 'Le conseil', 'On vous propose la prestation la plus adaptée — parfois moins chère que prévu.'],
                    ['3', 'Le soin', 'On travaille en douceur, avec des pauses et une installation confortable.'],
                    ['4', 'Le suivi', "On vous remet une routine simple et on planifie le prochain rendez-vous."],
                ] as [$num, $title, $text])
                    <div class="card flex gap-5 p-6" data-reveal>
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-gold-500/15 font-display text-xl font-semibold text-gold-600">{{ $num }}</span>
                        <div>
                            <h3 class="font-display text-xl font-semibold text-espresso-900">{{ $title }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-espresso-700/75">{{ $text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($staff->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <x-section-title eyebrow="L'équipe" title="Celles qui prennent soin de vous"
                             subtitle="Chaque membre de l'équipe a sa spécialité — vous pouvez demander votre praticienne lors de la réservation." />

            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($staff as $member)
                    <div data-reveal>
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
                        <p class="mt-3 text-sm leading-relaxed text-espresso-700/75">{{ $member->bio }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($testimonials->isNotEmpty())
        <section class="bg-cream-100 py-24">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <x-section-title eyebrow="Avis clientes" title="Ce qu'elles retiennent" />
                <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="card p-8" data-reveal>
                            <div class="flex gap-1 text-gold-500">
                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                    <x-icon name="star" class="h-4 w-4 fill-current" />
                                @endfor
                            </div>
                            <blockquote class="mt-5 font-display text-xl leading-relaxed text-espresso-900">« {{ $testimonial->content }} »</blockquote>
                            <figcaption class="mt-5 text-sm text-espresso-700/60">
                                <span class="font-medium text-espresso-900">{{ $testimonial->author }}</span>
                                @if ($testimonial->context) — {{ $testimonial->context }} @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="rounded-[2.5rem] bg-espresso-900 p-10 text-center text-cream-100 lg:p-16">
            <h2 class="font-display text-4xl font-semibold text-gold-300 sm:text-5xl">Venez nous rencontrer</h2>
            <p class="mx-auto mt-4 max-w-xl text-cream-100/75">{{ config('salon.address') }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('booking.create') }}" class="btn-gold">Prendre rendez-vous</a>
                <a href="{{ route('contact') }}" class="btn border border-cream-100/25 text-cream-100 hover:border-gold-300 hover:text-gold-300">Itinéraire & horaires</a>
            </div>
        </div>
    </section>

@endsection
