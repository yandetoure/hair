<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion') — {{ config('salon.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream-100 font-sans">

@php
    $nav = [
        ['admin.dashboard', 'dashboard', 'Tableau de bord', null],
        ['admin.appointments.agenda', 'calendar', 'Agenda', null],
        ['admin.appointments.index', 'users', 'Rendez-vous', \App\Models\Appointment::where('status', 'pending')->count()],
        ['admin.orders.index', 'bag', 'Commandes', \App\Models\Order::where('status', 'pending')->count()],
        ['admin.services.index', 'ciseaux', 'Prestations', null],
        ['admin.products.index', 'box', 'Boutique', null],
        ['admin.staff.index', 'sparkles', 'Équipe', null],
        ['admin.gallery.index', 'star', 'Galerie', null],
        ['admin.offers.index', 'wallet', 'Forfaits', null],
        ['admin.journal.index', 'edit', 'Journal', null],
        ['admin.reports.index', 'chart', 'Rapports', null],
    ];
@endphp

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside data-menu data-menu-display="flex"
           class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col overflow-y-auto bg-espresso-900 text-cream-100 lg:flex">
        <div class="flex items-center gap-3 px-6 py-6">
            <span class="grid h-10 w-10 place-items-center rounded-full bg-gold-500 font-display font-semibold text-white">MÉ</span>
            <div class="leading-tight">
                <span class="block font-display text-lg font-semibold">{{ config('salon.name') }}</span>
                <span class="block text-[10px] uppercase tracking-[0.2em] text-gold-300">Espace équipe</span>
            </div>
        </div>

        <nav class="flex-1 space-y-1 px-3 py-4">
            @foreach ($nav as [$route, $icon, $label, $badge])
                @php $active = request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route)); @endphp
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ $active ? 'bg-gold-500/15 text-gold-300' : 'text-cream-100/70 hover:bg-white/5 hover:text-cream-100' }}">
                    <x-icon :name="$icon" class="h-4.5 w-4.5" />
                    <span class="flex-1">{{ $label }}</span>
                    @if ($badge)
                        <span class="rounded-full bg-blush-500 px-2 py-0.5 text-[10px] font-semibold text-white">{{ $badge }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-4">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-cream-100/70 transition hover:text-gold-300">
                <x-icon name="arrow-right" class="h-4 w-4" /> Voir le site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-cream-100/70 transition hover:text-rose-300">
                    <x-icon name="logout" class="h-4 w-4" /> Déconnexion
                </button>
            </form>
            <p class="mt-3 px-3 text-[11px] text-cream-100/40">Connectée : {{ auth()->user()->name }}</p>
        </div>
    </aside>

    {{-- Contenu --}}
    <div class="flex min-h-screen flex-1 flex-col lg:ml-64">
        <header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-espresso-900/10 bg-cream-100/95 px-5 py-4 backdrop-blur lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" data-menu-toggle class="grid h-10 w-10 place-items-center rounded-xl border border-espresso-900/15 lg:hidden">
                    <x-icon name="menu" class="h-5 w-5" />
                </button>
                <div>
                    <h1 class="font-display text-2xl font-semibold text-espresso-900">@yield('heading', 'Tableau de bord')</h1>
                    <p class="text-xs text-espresso-700/60">@yield('subheading', ucfirst(fr_date(now())))</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @yield('actions')
                <a href="{{ route('admin.appointments.create') }}" class="btn-gold hidden py-2.5 text-xs sm:inline-flex">
                    <x-icon name="plus" class="h-4 w-4" /> Nouveau RDV
                </a>
            </div>
        </header>

        @if (session('success') || session('error') || $errors->any())
            <div class="px-5 pt-5 lg:px-8">
                @if (session('success'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <ul class="list-disc space-y-0.5 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        <main class="flex-1 px-5 py-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
