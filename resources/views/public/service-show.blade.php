@extends('layouts.public')

@section('title', $service->name.' — '.config('salon.name'))
@section('description', $service->description)

@section('content')

    <section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
        <nav class="flex items-center gap-2 text-xs uppercase tracking-[0.16em] text-espresso-700/50">
            <a href="{{ route('services.index') }}" class="hover:text-gold-600">Prestations</a>
            <span>/</span>
            <span class="text-gold-600">{{ $service->category?->name }}</span>
        </nav>

        <div class="mt-10 grid gap-14 lg:grid-cols-2">
            <div>
                <x-media :src="$service->image" :alt="$service->name" :icon="$service->category?->icon ?? 'sparkles'"
                         ratio="aspect-[4/3]" class="shadow-[0_30px_70px_-40px_rgba(51,34,28,0.6)]" />
            </div>

            <div>
                <span class="eyebrow">{{ $service->category?->name }}</span>
                <h1 class="mt-3 font-display text-5xl font-semibold leading-tight text-espresso-900">{{ $service->name }}</h1>
                <p class="mt-5 text-lg leading-relaxed text-espresso-700/85">{{ $service->description }}</p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="card p-5">
                        <span class="text-xs uppercase tracking-[0.16em] text-espresso-700/55">Tarif</span>
                        <p class="mt-1 font-display text-3xl font-semibold text-gold-600">{{ $service->price_label }}</p>
                    </div>
                    <div class="card p-5">
                        <span class="text-xs uppercase tracking-[0.16em] text-espresso-700/55">Durée</span>
                        <p class="mt-1 font-display text-3xl font-semibold text-espresso-900">{{ $service->duration_label }}</p>
                    </div>
                </div>

                <ul class="mt-8 space-y-3 text-sm text-espresso-700/85">
                    @foreach ([
                        'Diagnostic et conseils personnalisés inclus',
                        'Produits professionnels adaptés à votre nature de cheveu',
                        'Confirmation du rendez-vous par téléphone ou WhatsApp',
                    ] as $point)
                        <li class="flex gap-3">
                            <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-gold-500/15 text-gold-600">
                                <x-icon name="check" class="h-3 w-3" />
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="{{ route('booking.create', ['service' => $service->slug]) }}" class="btn-gold px-8 py-4">
                        Réserver cette prestation
                    </a>
                    <a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="btn-outline px-8 py-4">
                        <x-icon name="phone" class="h-4 w-4" /> Appeler le salon
                    </a>
                </div>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-24">
                <x-section-title align="left" eyebrow="À découvrir aussi" title="Dans le même univers" />
                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-service-card :service="$item" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>

@endsection
