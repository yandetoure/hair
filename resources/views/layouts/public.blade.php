<!DOCTYPE html>
<html lang="fr" class="scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('salon.name').' — '.config('salon.tagline'))</title>
    <meta name="description" content="@yield('description', config('salon.baseline').' — '.config('salon.address'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
@php
    $cartCount = app(\App\Services\Cart::class)->count();

    $navLinks = [
        'salon' => 'Le salon',
        'services.index' => 'Prestations',
        'gallery.index' => 'Réalisations',
        'offers.index' => 'Forfaits',
        'shop.index' => 'Boutique',
        'journal.index' => 'Journal',
        'contact' => 'Contact',
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-espresso-900/10 bg-cream-50/90 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-full bg-espresso-900 font-display text-lg font-semibold text-gold-300">MÉ</span>
            <span class="leading-tight">
                <span class="block font-display text-xl font-semibold tracking-wide text-espresso-900">{{ config('salon.name') }}</span>
                <span class="block text-[10px] uppercase tracking-[0.26em] text-gold-600">{{ config('salon.tagline') }}</span>
            </span>
        </a>

        <nav class="hidden items-center gap-6 text-sm xl:flex">
            @foreach ($navLinks as $route => $label)
                <a href="{{ route($route) }}"
                   class="relative py-1 transition {{ request()->routeIs($route) ? 'text-gold-600' : 'text-espresso-800 hover:text-gold-600' }}">
                    {{ $label }}
                    @if (request()->routeIs($route))
                        <span class="absolute inset-x-0 -bottom-0.5 h-px bg-gold-500"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('cart.index') }}" class="relative grid h-11 w-11 place-items-center rounded-full border border-espresso-900/15 text-espresso-900 transition hover:border-gold-500 hover:text-gold-600" aria-label="Panier">
                <x-icon name="bag" class="h-5 w-5" />
                @if ($cartCount)
                    <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-blush-500 px-1 text-[10px] font-semibold text-white">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="{{ route('booking.create') }}" class="btn-gold hidden sm:inline-flex">Prendre rendez-vous</a>
            <button type="button" data-menu-toggle class="grid h-11 w-11 place-items-center rounded-full border border-espresso-900/15 xl:hidden" aria-label="Menu">
                <x-icon name="menu" class="h-5 w-5" />
            </button>
        </div>
    </div>

    <div data-menu class="hidden border-t border-espresso-900/10 bg-cream-50 xl:hidden">
        <nav class="mx-auto flex max-w-7xl flex-col px-5 py-3 text-sm">
            @foreach ($navLinks as $route => $label)
                <a href="{{ route($route) }}" class="border-b border-espresso-900/5 py-3 {{ request()->routeIs($route) ? 'text-gold-600' : '' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('booking.create') }}" class="btn-gold mt-4">Prendre rendez-vous</a>
        </nav>
    </div>
</header>

@if (session('success') || session('error') || $errors->any())
    <div class="mx-auto max-w-7xl px-5 pt-5 lg:px-8">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-medium">Merci de corriger les points suivants :</p>
                <ul class="mt-1 list-disc space-y-0.5 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif

<main>
    @yield('content')
</main>

<footer class="mt-24 bg-espresso-900 text-cream-100">
    <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 lg:grid-cols-4 lg:px-8">
        <div class="lg:col-span-1">
            <span class="font-display text-2xl font-semibold text-gold-300">{{ config('salon.name') }}</span>
            <p class="mt-4 text-sm leading-relaxed text-cream-100/70">{{ config('salon.baseline') }}</p>
            <div class="mt-6 flex gap-3">
                <a href="{{ config('salon.instagram') }}" class="grid h-10 w-10 place-items-center rounded-full border border-cream-100/20 transition hover:border-gold-400 hover:text-gold-300" aria-label="Instagram">
                    <x-icon name="instagram" class="h-4 w-4" />
                </a>
                <a href="{{ config('salon.facebook') }}" class="grid h-10 w-10 place-items-center rounded-full border border-cream-100/20 transition hover:border-gold-400 hover:text-gold-300" aria-label="Facebook">
                    <x-icon name="facebook" class="h-4 w-4" />
                </a>
            </div>
        </div>

        <div>
            <h3 class="text-xs uppercase tracking-[0.24em] text-gold-300">Navigation</h3>
            <ul class="mt-5 space-y-3 text-sm text-cream-100/75">
                <li><a href="{{ route('salon') }}" class="hover:text-gold-300">Le salon</a></li>
                <li><a href="{{ route('services.index') }}" class="hover:text-gold-300">Prestations & tarifs</a></li>
                <li><a href="{{ route('gallery.index') }}" class="hover:text-gold-300">Nos réalisations</a></li>
                <li><a href="{{ route('offers.index') }}" class="hover:text-gold-300">Forfaits & rituels</a></li>
                <li><a href="{{ route('shop.index') }}" class="hover:text-gold-300">Boutique</a></li>
                <li><a href="{{ route('journal.index') }}" class="hover:text-gold-300">Journal beauté</a></li>
                <li><a href="{{ route('booking.create') }}" class="hover:text-gold-300">Réserver</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-xs uppercase tracking-[0.24em] text-gold-300">Horaires</h3>
            <ul class="mt-5 space-y-2 text-sm text-cream-100/75">
                @foreach ([1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'] as $i => $day)
                    @php $range = config('salon.hours')[$i] ?? null; @endphp
                    <li class="flex justify-between gap-4">
                        <span>{{ $day }}</span>
                        <span class="{{ $range ? 'text-cream-100' : 'text-cream-100/40' }}">{{ $range ? $range[0].' – '.$range[1] : 'Fermé' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-xs uppercase tracking-[0.24em] text-gold-300">Contact</h3>
            <ul class="mt-5 space-y-3 text-sm text-cream-100/75">
                <li>{{ config('salon.address') }}</li>
                <li><a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="hover:text-gold-300">{{ config('salon.phone') }}</a></li>
                <li><a href="mailto:{{ config('salon.email') }}" class="hover:text-gold-300">{{ config('salon.email') }}</a></li>
            </ul>
            <a href="{{ route('booking.create') }}" class="btn-gold mt-6">Réserver une séance</a>
        </div>
    </div>

    <div class="border-t border-cream-100/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-5 text-xs text-cream-100/50 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <span>© {{ date('Y') }} {{ config('salon.name') }}. Tous droits réservés.</span>
            <a href="{{ route('login') }}" class="hover:text-gold-300">Espace équipe</a>
        </div>
    </div>
</footer>

<a href="https://wa.me/{{ preg_replace('/\D+/', '', config('salon.whatsapp')) }}"
   class="fixed bottom-6 right-6 z-40 grid h-14 w-14 place-items-center rounded-full bg-emerald-500 text-white shadow-lg transition hover:bg-emerald-600"
   aria-label="WhatsApp">
    <x-icon name="whatsapp" class="h-7 w-7" />
</a>

@stack('scripts')
</body>
</html>
