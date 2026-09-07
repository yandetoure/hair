@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('heading', 'Tableau de bord')
@section('subheading', ucfirst(fr_date(now())))

@section('actions')
    <a href="{{ route('admin.reports.index') }}" class="btn-outline py-2.5 text-xs">
        <x-icon name="chart" class="h-4 w-4" /> Rapports
    </a>
@endsection

@section('content')

@php
    $trend = fn ($now, $before) => $before > 0 ? round((($now - $before) / $before) * 100, 1) : null;
@endphp

{{-- KPI du jour --}}
<section>
    <h2 class="mb-3 text-xs uppercase tracking-[0.2em] text-espresso-700/50">Aujourd'hui</h2>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat label="Rendez-vous du jour" :value="$today['appointments_total']" icon="calendar"
                      :hint="$today['appointments_pending'].' en attente de confirmation'" />
        <x-admin.stat label="CA prestations (jour)" :value="fcfa($today['revenue_services_expected'])" icon="ciseaux" tone="blush"
                      :hint="$today['appointments_completed'].' prestations terminées'" />
        <x-admin.stat label="Commandes du jour" :value="$today['orders_total']" icon="bag" tone="sky"
                      :hint="fcfa($today['revenue_products']).' encaissés'" />
        <x-admin.stat label="Occupation de la semaine" :value="$occupancyWeek.' %'" icon="chart" tone="emerald"
                      hint="postes occupés / capacité" />
    </div>
</section>

{{-- KPI semaine & mois --}}
<section class="mt-8 grid gap-4 lg:grid-cols-3">
    <div class="card p-6">
        <h3 class="text-xs uppercase tracking-[0.2em] text-espresso-700/50">Cette semaine</h3>
        <p class="mt-3 font-display text-4xl font-semibold text-espresso-900">{{ $week['appointments_total'] }} <span class="text-lg font-normal text-espresso-700/50">rendez-vous</span></p>
        <dl class="mt-5 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-espresso-700/60">CA prestations</dt><dd class="font-medium">{{ fcfa($week['revenue_services_expected']) }}</dd></div>
            <div class="flex justify-between"><dt class="text-espresso-700/60">CA boutique</dt><dd class="font-medium">{{ fcfa($week['revenue_products']) }}</dd></div>
            <div class="flex justify-between border-t border-espresso-900/8 pt-2"><dt class="font-medium text-espresso-900">Total</dt><dd class="font-display text-lg font-semibold text-gold-600">{{ fcfa($week['revenue_total']) }}</dd></div>
        </dl>
    </div>

    <div class="card p-6">
        <h3 class="text-xs uppercase tracking-[0.2em] text-espresso-700/50">Ce mois-ci</h3>
        <p class="mt-3 font-display text-4xl font-semibold text-espresso-900">{{ fcfa($month['revenue_total']) }}</p>
        @php $t = $trend($month['revenue_total'], $lastMonth['revenue_total']); @endphp
        <div class="mt-2 flex items-center gap-2 text-xs">
            @if (! is_null($t))
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 font-medium {{ $t >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                    {{ $t >= 0 ? '▲' : '▼' }} {{ number_format(abs($t), 1, ',', ' ') }} %
                </span>
            @endif
            <span class="text-espresso-700/55">vs mois précédent ({{ fcfa($lastMonth['revenue_total']) }})</span>
        </div>
        <dl class="mt-5 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-espresso-700/60">Rendez-vous</dt><dd class="font-medium">{{ $month['appointments_total'] }}</dd></div>
            <div class="flex justify-between"><dt class="text-espresso-700/60">Commandes</dt><dd class="font-medium">{{ $month['orders_total'] }}</dd></div>
            <div class="flex justify-between"><dt class="text-espresso-700/60">Ticket moyen</dt><dd class="font-medium">{{ fcfa($month['ticket_moyen']) }}</dd></div>
        </dl>
    </div>

    <div class="card p-6">
        <h3 class="text-xs uppercase tracking-[0.2em] text-espresso-700/50">Suivi du mois</h3>
        <dl class="mt-4 space-y-3 text-sm">
            @foreach ([
                ['En attente', $month['appointments_pending'], 'bg-amber-400'],
                ['Confirmés', $month['appointments_confirmed'], 'bg-sky-400'],
                ['Terminés', $month['appointments_completed'], 'bg-emerald-400'],
                ['Annulés / absents', $month['appointments_cancelled'], 'bg-rose-400'],
            ] as [$label, $value, $color])
                @php $pct = $month['appointments_total'] > 0 ? $value / $month['appointments_total'] * 100 : 0; @endphp
                <div>
                    <div class="flex justify-between"><dt class="text-espresso-700/65">{{ $label }}</dt><dd class="font-medium">{{ $value }}</dd></div>
                    <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-cream-200">
                        <div class="h-full rounded-full {{ $color }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </dl>
        <p class="mt-4 text-xs text-espresso-700/55">{{ $month['clients'] }} clientes distinctes ce mois-ci.</p>
    </div>
</section>

{{-- Graphique + agenda --}}
<section class="mt-8 grid gap-6 xl:grid-cols-3">
    <div class="card p-6 xl:col-span-2">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-display text-xl font-semibold text-espresso-900">Chiffre d'affaires — 14 derniers jours</h3>
                <p class="text-xs text-espresso-700/55">Prestations réservées + commandes boutique</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-xs text-gold-600 hover:underline">Voir le rapport</a>
        </div>

        <x-admin.bar-chart class="mt-6"
                           :rows="$series->map(fn ($r) => ['label' => $r['label'], 'value' => $r['services_revenue'], 'secondary' => $r['orders_revenue']])"
                           :legend="['Prestations', 'Boutique']" :height="200" />
    </div>

    <div class="card flex flex-col p-6">
        <div class="flex items-center justify-between">
            <h3 class="font-display text-xl font-semibold text-espresso-900">Agenda du jour</h3>
            <a href="{{ route('admin.appointments.agenda', ['vue' => 'jour']) }}" class="text-xs text-gold-600 hover:underline">Ouvrir</a>
        </div>

        @if ($agendaToday->isEmpty())
            <p class="mt-8 flex-1 text-center text-sm text-espresso-700/50">Aucun rendez-vous aujourd'hui.</p>
        @else
            <ul class="mt-5 max-h-[420px] space-y-3 overflow-y-auto pr-1">
                @foreach ($agendaToday as $appointment)
                    <li class="flex gap-3 rounded-xl border border-espresso-900/8 p-3">
                        <div class="w-12 shrink-0 text-center">
                            <span class="block font-display text-lg font-semibold text-espresso-900">{{ $appointment->starts_at->format('H:i') }}</span>
                            <span class="block text-[10px] text-espresso-700/45">{{ $appointment->ends_at->format('H:i') }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-espresso-900">{{ $appointment->customer_name }}</p>
                            <p class="truncate text-xs text-espresso-700/60">{{ $appointment->service?->name }}</p>
                            <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-[10px] font-medium ring-1 {{ $appointment->status_color }}">
                                {{ $appointment->status_label }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>

{{-- Listes --}}
<section class="mt-8 grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-espresso-900">Prochains rendez-vous</h3>
        <ul class="mt-4 space-y-3 text-sm">
            @forelse ($upcoming as $appointment)
                <li class="flex items-center justify-between gap-3 border-b border-espresso-900/5 pb-3 last:border-0">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-espresso-900">{{ $appointment->customer_name }}</p>
                        <p class="truncate text-xs text-espresso-700/60">{{ $appointment->service?->name }}</p>
                    </div>
                    <span class="whitespace-nowrap text-xs text-espresso-700/70">
                        {{ $appointment->starts_at->locale('fr')->isoFormat('ddd D/MM') }} · {{ $appointment->starts_at->format('H:i') }}
                    </span>
                </li>
            @empty
                <li class="py-6 text-center text-espresso-700/50">Aucun rendez-vous à venir.</li>
            @endforelse
        </ul>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold text-espresso-900">Commandes à traiter</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-gold-600 hover:underline">Toutes</a>
        </div>
        <ul class="mt-4 space-y-3 text-sm">
            @forelse ($pendingOrders as $order)
                <li class="flex items-center justify-between gap-3 border-b border-espresso-900/5 pb-3 last:border-0">
                    <div class="min-w-0">
                        <a href="{{ route('admin.orders.show', $order) }}" class="truncate font-medium text-espresso-900 hover:text-gold-600">{{ $order->customer_name }}</a>
                        <p class="text-xs text-espresso-700/60">{{ $order->items->sum('quantity') }} article(s) · {{ $order->reference }}</p>
                    </div>
                    <span class="whitespace-nowrap font-medium text-gold-600">{{ fcfa($order->total) }}</span>
                </li>
            @empty
                <li class="py-6 text-center text-espresso-700/50">Aucune commande en attente.</li>
            @endforelse
        </ul>
    </div>

    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-espresso-900">Top prestations (mois)</h3>
        <div class="mt-4">
            <x-admin.rank-list :rows="$topServices" unit="RDV" />
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-espresso-900">Top produits (mois)</h3>
        <div class="mt-4">
            <x-admin.rank-list :rows="$topProducts" unit="vendus" />
        </div>

        @if ($lowStock->isNotEmpty())
            <div class="mt-6 rounded-xl bg-amber-50 p-4">
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-amber-700">Stock faible</p>
                <ul class="mt-2 space-y-1 text-xs text-amber-800">
                    @foreach ($lowStock as $product)
                        <li class="flex justify-between gap-3">
                            <span class="truncate">{{ $product->name }}</span>
                            <span class="font-medium">{{ $product->stock }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</section>

@endsection
