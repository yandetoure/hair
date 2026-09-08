@extends('layouts.public')

@section('title', $post->title.' — '.config('salon.name'))
@section('description', $post->excerpt)

@section('content')

    <article class="mx-auto max-w-3xl px-5 py-16 lg:px-8">
        <nav class="flex items-center gap-2 text-xs uppercase tracking-[0.16em] text-espresso-700/50">
            <a href="{{ route('journal.index') }}" class="hover:text-gold-600">Journal</a>
            <span>/</span>
            <span class="text-gold-600">{{ $post->category }}</span>
        </nav>

        <h1 class="mt-6 font-display text-4xl font-semibold leading-tight text-espresso-900 sm:text-5xl">{{ $post->title }}</h1>

        <div class="mt-5 flex flex-wrap items-center gap-4 text-xs uppercase tracking-[0.16em] text-espresso-700/55">
            @if ($post->published_at)
                <span>{{ $post->published_at->locale('fr')->isoFormat('D MMMM YYYY') }}</span>
            @endif
            <span>· {{ $post->reading_time }} min de lecture</span>
            <span>· par l'équipe {{ config('salon.name') }}</span>
        </div>

        @if ($post->excerpt)
            <p class="mt-8 border-l-2 border-gold-500 pl-5 font-display text-2xl leading-relaxed text-espresso-800">
                {{ $post->excerpt }}
            </p>
        @endif

        <x-media :src="$post->image" :alt="$post->title" ratio="aspect-[16/9]" class="mt-10" />

        <div class="mt-12 space-y-6">
            @foreach ($post->blocks as $block)
                @if ($block['type'] === 'heading')
                    <h2 class="pt-4 font-display text-3xl font-semibold text-espresso-900">{{ $block['text'] }}</h2>
                @elseif ($block['type'] === 'list')
                    <ul class="space-y-2.5">
                        @foreach ($block['items'] as $item)
                            <li class="flex gap-3 text-espresso-700/85">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-gold-500"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-lg leading-relaxed text-espresso-700/85">{{ $block['text'] }}</p>
                @endif
            @endforeach
        </div>

        <div class="mt-14 rounded-2xl bg-cream-100 p-8 text-center">
            <p class="font-display text-2xl font-semibold text-espresso-900">Envie de passer à la pratique ?</p>
            <p class="mx-auto mt-2 max-w-md text-sm text-espresso-700/80">
                Nos expertes appliquent ces gestes en cabine et vous montrent comment les reproduire chez vous.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a href="{{ route('booking.create') }}" class="btn-gold">Prendre rendez-vous</a>
                <a href="{{ route('shop.index') }}" class="btn-outline">Voir les produits</a>
            </div>
        </div>
    </article>

    @if ($others->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 pb-24 lg:px-8">
            <x-section-title align="left" eyebrow="À lire aussi" title="Dans le même esprit" />
            <div class="mt-10 grid gap-10 sm:grid-cols-2">
                @foreach ($others as $other)
                    <article class="group" data-reveal>
                        <a href="{{ route('journal.show', $other) }}">
                            <x-media :src="$other->image" :alt="$other->title" ratio="aspect-[5/3]" />
                        </a>
                        <h3 class="mt-4 font-display text-2xl font-semibold text-espresso-900 transition group-hover:text-gold-600">
                            <a href="{{ route('journal.show', $other) }}">{{ $other->title }}</a>
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-espresso-700/80">{{ $other->excerpt }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

@endsection
