@extends('layouts.public')

@section('title', 'Contact & accès — '.config('salon.name'))
@section('description', 'Adresse, horaires et contacts du salon '.config('salon.name').' à Abidjan.')

@section('content')

    <section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        <div class="grid gap-14 lg:grid-cols-2">
            <div>
                <span class="eyebrow">Contact</span>
                <h1 class="mt-4 font-display text-5xl font-semibold leading-tight text-espresso-900">Parlons de vos cheveux</h1>
                <p class="mt-5 text-lg leading-relaxed text-espresso-700/85">
                    Une question sur une prestation, un devis pour un mariage, une commande à retirer ?
                    Écrivez-nous ou appelez-nous, nous répondons dans la journée.
                </p>

                <div class="mt-10 space-y-4">
                    @foreach ([
                        ['map-pin', 'Adresse', config('salon.address'), null],
                        ['phone', 'Téléphone', config('salon.phone'), 'tel:'.preg_replace('/\s+/', '', config('salon.phone'))],
                        ['whatsapp', 'WhatsApp', config('salon.whatsapp'), 'https://wa.me/'.preg_replace('/\D+/', '', config('salon.whatsapp'))],
                        ['mail', 'E-mail', config('salon.email'), 'mailto:'.config('salon.email')],
                    ] as [$icon, $label, $value, $href])
                        <div class="card flex items-center gap-5 p-5">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-cream-100 text-gold-600">
                                <x-icon :name="$icon" class="h-5 w-5" />
                            </span>
                            <div>
                                <span class="text-xs uppercase tracking-[0.16em] text-espresso-700/55">{{ $label }}</span>
                                @if ($href)
                                    <a href="{{ $href }}" class="block text-lg text-espresso-900 transition hover:text-gold-600">{{ $value }}</a>
                                @else
                                    <p class="text-lg text-espresso-900">{{ $value }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('booking.create') }}" class="btn-gold">Prendre rendez-vous</a>
                    <a href="{{ route('shop.index') }}" class="btn-outline">Commander un produit</a>
                </div>
            </div>

            <div>
                <div class="overflow-hidden rounded-2xl">
                    <x-media src="images/salon/accueil.jpg" alt="Devanture du salon" ratio="aspect-[4/3]" :overlay="true">
                        <div class="pointer-events-none absolute inset-x-0 bottom-0 p-7 text-cream-100">
                            <x-icon name="map-pin" class="h-8 w-8 text-gold-300" />
                            <p class="mt-3 font-display text-2xl font-semibold">{{ config('salon.address') }}</p>
                            <p class="mt-1 text-xs uppercase tracking-[0.16em] text-cream-100/70">Parking disponible sur place</p>
                        </div>
                    </x-media>
                </div>

                <div class="card mt-6 p-8">
                    <h2 class="font-display text-2xl font-semibold text-espresso-900">Horaires d'ouverture</h2>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ([1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'] as $i => $day)
                            @php $range = config('salon.hours')[$i] ?? null; @endphp
                            <li class="flex items-center justify-between gap-4 border-b border-espresso-900/5 pb-3 last:border-0 {{ now()->isoWeekday() === $i ? 'text-gold-600' : 'text-espresso-700/80' }}">
                                <span class="{{ now()->isoWeekday() === $i ? 'font-medium' : '' }}">{{ $day }}</span>
                                <span>{{ $range ? $range[0].' – '.$range[1] : 'Fermé' }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-5 text-xs leading-relaxed text-espresso-700/60">
                        Dernier rendez-vous accepté 1h avant la fermeture. Merci de prévenir au moins 24h à l'avance
                        en cas d'annulation.
                    </p>
                </div>
            </div>
        </div>
    </section>

@endsection
