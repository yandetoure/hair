@extends('layouts.public')

@section('title', 'Nos réalisations — '.config('salon.name'))
@section('description', 'Tresses, coiffures, colorations, nail art et mises en beauté réalisées au salon '.config('salon.name').' à Abidjan.')

@section('content')

    <section class="relative overflow-hidden border-b border-espresso-900/10">
        <div class="texture-gradient absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto max-w-3xl px-5 py-20 text-center lg:px-8">
            <span class="eyebrow">Galerie</span>
            <h1 class="mt-4 font-display text-5xl font-semibold text-espresso-900 sm:text-6xl">Nos réalisations</h1>
            <p class="mt-5 text-lg text-espresso-700/85">
                Chaque coiffure est pensée pour une personne, une nature de cheveu et un mode de vie.
                Voici un aperçu de notre travail — et de ce que nous pouvons imaginer pour vous.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-14 lg:px-8">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="{{ route('gallery.index') }}"
               class="rounded-full border px-5 py-2 text-sm transition {{ $active ? 'border-espresso-900/15 hover:border-gold-500' : 'border-gold-500 bg-gold-500/10 text-gold-600' }}">
                Tout voir
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('gallery.index', ['univers' => $category]) }}"
                   class="rounded-full border px-5 py-2 text-sm transition {{ $active === $category ? 'border-gold-500 bg-gold-500/10 text-gold-600' : 'border-espresso-900/15 hover:border-gold-500' }}">
                    {{ $category }}
                </a>
            @endforeach
        </div>

        @if ($items->isEmpty())
            <p class="mt-16 rounded-2xl border border-dashed border-espresso-900/15 py-16 text-center text-espresso-700/60">
                Aucune réalisation dans cet univers pour le moment.
            </p>
        @else
            <div class="mt-12 columns-1 gap-5 sm:columns-2 lg:columns-3 [&>*]:mb-5">
                @foreach ($items as $item)
                    <figure class="group break-inside-avoid overflow-hidden rounded-2xl" data-reveal>
                        <x-media :src="$item->image" :alt="$item->title" :overlay="true"
                                 :ratio="$item->format === 'portrait' ? 'aspect-[4/5]' : 'aspect-[4/3]'">
                            <figcaption class="pointer-events-none absolute inset-x-0 bottom-0 p-6">
                                <span class="text-[10px] uppercase tracking-[0.22em] text-gold-300">{{ $item->category }}</span>
                                <p class="mt-1 font-display text-2xl font-semibold leading-snug text-cream-100">{{ $item->title }}</p>
                                <p class="mt-1 max-w-sm text-sm leading-relaxed text-cream-100/0 transition duration-300 group-hover:text-cream-100/80">
                                    {{ $item->description }}
                                </p>
                            </figcaption>
                        </x-media>
                    </figure>
                @endforeach
            </div>
        @endif

        <div class="mt-20 rounded-[2rem] bg-espresso-900 p-10 text-center text-cream-100 lg:p-14">
            <h2 class="font-display text-3xl font-semibold text-gold-300 sm:text-4xl">Une inspiration en tête ?</h2>
            <p class="mx-auto mt-3 max-w-xl text-cream-100/75">
                Apportez votre photo de référence : nous vous dirons ce qui est réalisable sur votre nature de cheveu,
                en combien de temps et à quel tarif.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('booking.create') }}" class="btn-gold">Prendre rendez-vous</a>
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('salon.whatsapp')) }}"
                   class="btn border border-cream-100/25 text-cream-100 hover:border-gold-300 hover:text-gold-300">
                    <x-icon name="whatsapp" class="h-4 w-4" /> Envoyer une photo
                </a>
            </div>
        </div>
    </section>

@endsection
