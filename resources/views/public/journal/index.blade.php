@extends('layouts.public')

@section('title', 'Journal beauté — '.config('salon.name'))
@section('description', "Conseils capillaires, routines et gestes du quotidien : ce que notre équipe explique en cabine, écrit pour vous.")

@section('content')

    <section class="relative overflow-hidden border-b border-espresso-900/10">
        <div class="texture-gradient absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto max-w-3xl px-5 py-20 text-center lg:px-8">
            <span class="eyebrow">Journal beauté</span>
            <h1 class="mt-4 font-display text-5xl font-semibold text-espresso-900 sm:text-6xl">Nos conseils, sans blabla</h1>
            <p class="mt-5 text-lg text-espresso-700/85">
                Les questions qui reviennent le plus souvent au salon, et les réponses que nous donnons vraiment.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        @if ($posts->isEmpty())
            <p class="rounded-2xl border border-dashed border-espresso-900/15 py-16 text-center text-espresso-700/60">
                Aucun article pour le moment.
            </p>
        @else
            @php $lead = $posts->first(); $rest = $posts->skip(1); @endphp

            {{-- Article à la une --}}
            <article class="grid items-center gap-12 lg:grid-cols-2" data-reveal>
                <a href="{{ route('journal.show', $lead) }}" class="group block">
                    <x-media :src="$lead->image" :alt="$lead->title" ratio="aspect-[4/3]"
                             class="rounded-[2rem] shadow-[0_40px_80px_-45px_rgba(51,34,28,0.55)]" />
                </a>
                <div>
                    <div class="flex items-center gap-3 text-xs uppercase tracking-[0.18em] text-gold-600">
                        <span>{{ $lead->category }}</span>
                        <span class="text-espresso-700/40">· {{ $lead->reading_time }} min</span>
                        @if ($lead->published_at)
                            <span class="text-espresso-700/40">· {{ $lead->published_at->locale('fr')->isoFormat('D MMMM YYYY') }}</span>
                        @endif
                    </div>
                    <h2 class="mt-3 font-display text-4xl font-semibold leading-tight text-espresso-900">
                        <a href="{{ route('journal.show', $lead) }}" class="transition hover:text-gold-600">{{ $lead->title }}</a>
                    </h2>
                    <p class="mt-4 text-lg leading-relaxed text-espresso-700/85">{{ $lead->excerpt }}</p>
                    <a href="{{ route('journal.show', $lead) }}" class="btn-gold mt-7">
                        Lire l'article <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>
            </article>

            @if ($rest->isNotEmpty())
                <div class="mt-20 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($rest as $post)
                        <article class="group" data-reveal>
                            <a href="{{ route('journal.show', $post) }}">
                                <x-media :src="$post->image" :alt="$post->title" ratio="aspect-[5/3]" />
                            </a>
                            <div class="mt-5">
                                <div class="flex items-center gap-3 text-xs uppercase tracking-[0.16em] text-gold-600">
                                    <span>{{ $post->category }}</span>
                                    <span class="text-espresso-700/40">· {{ $post->reading_time }} min</span>
                                </div>
                                <h3 class="mt-2 font-display text-2xl font-semibold leading-snug text-espresso-900 transition group-hover:text-gold-600">
                                    <a href="{{ route('journal.show', $post) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="mt-2 text-sm leading-relaxed text-espresso-700/80">{{ $post->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-20 rounded-[2rem] bg-cream-100 p-10 text-center lg:p-14">
            <h2 class="font-display text-3xl font-semibold text-espresso-900">Une question sur vos cheveux ?</h2>
            <p class="mx-auto mt-3 max-w-xl text-espresso-700/80">
                Le diagnostic capillaire est offert lors de votre première visite : on regarde, on explique,
                et on vous propose la routine la plus simple à tenir.
            </p>
            <a href="{{ route('booking.create') }}" class="btn-gold mt-7">Réserver mon diagnostic</a>
        </div>
    </section>

@endsection
