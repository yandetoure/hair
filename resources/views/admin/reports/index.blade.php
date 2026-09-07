@extends('layouts.admin')

@section('title', 'Rapports')
@section('heading', 'Rapports & analyses')
@section('subheading', 'Du '.$from->format('d/m/Y').' au '.$to->format('d/m/Y'))

@php
    $trend = fn ($now, $before) => $before > 0 ? round((($now - $before) / $before) * 100, 1) : null;
    $params = ['periode' => $period] + array_intersect_key($query, array_flip(['from', 'to']));
@endphp

@section('actions')
    <a href="{{ route('admin.reports.print', $params) }}" target="_blank" class="btn-outline py-2.5 text-xs">
        <x-icon name="printer" class="h-4 w-4" /> Imprimer
    </a>
    <a href="{{ route('admin.reports.export', $params) }}" class="btn-dark py-2.5 text-xs">
        <x-icon name="download" class="h-4 w-4" /> Export CSV
    </a>
@endsection

@section('content')

{{-- Sélecteur de période --}}
<form method="GET" class="card mb-6 flex flex-wrap items-end gap-4 p-4">
    <div class="flex flex-wrap gap-1 rounded-full border border-espresso-900/12 p-1">
        @foreach (['jour' => "Aujourd'hui", 'semaine' => 'Cette semaine', 'mois' => 'Ce mois', 'trimestre' => 'Trimestre', 'annee' => 'Année'] as $key => $label)
            <a href="{{ route('admin.reports.index', ['periode' => $key]) }}"
               class="rounded-full px-4 py-1.5 text-xs transition {{ $period === $key ? 'bg-espresso-900 text-cream-100' : 'text-espresso-700/70 hover:text-espresso-900' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="flex flex-wrap items-end gap-2">
        <input type="hidden" name="periode" value="perso">
        <div>
            <label class="label" for="from">Du</label>
            <input type="date" name="from" id="from" class="field py-2 text-xs" value="{{ $from->toDateString() }}">
        </div>
        <div>
            <label class="label" for="to">Au</label>
            <input type="date" name="to" id="to" class="field py-2 text-xs" value="{{ $to->toDateString() }}">
        </div>
        <button class="btn-gold py-2.5 text-xs">Appliquer</button>
    </div>
</form>

{{-- Synthèse --}}
<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-admin.stat label="Chiffre d'affaires total" :value="fcfa($kpis['revenue_total'])" icon="wallet"
                  :trend="$trend($kpis['revenue_total'], $previous['revenue_total'])" hint="vs période précédente" />
    <x-admin.stat label="CA prestations" :value="fcfa($kpis['revenue_services_expected'])" icon="ciseaux" tone="blush"
                  :trend="$trend($kpis['revenue_services_expected'], $previous['revenue_services_expected'])"
                  :hint="fcfa($kpis['revenue_services_done']).' réalisés'" />
    <x-admin.stat label="CA boutique" :value="fcfa($kpis['revenue_products'])" icon="bag" tone="sky"
                  :trend="$trend($kpis['revenue_products'], $previous['revenue_products'])"
                  :hint="$kpis['orders_total'].' commandes'" />
    <x-admin.stat label="Ticket moyen" :value="fcfa($kpis['ticket_moyen'])" icon="chart" tone="emerald"
                  :trend="$trend($kpis['ticket_moyen'], $previous['ticket_moyen'])"
                  :hint="$kpis['clients'].' clientes'" />
</section>

<section class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-admin.stat label="Rendez-vous" :value="$kpis['appointments_total']" icon="calendar" tone="espresso"
                  :trend="$trend($kpis['appointments_total'], $previous['appointments_total'])" />
    <x-admin.stat label="Prestations réalisées" :value="$kpis['appointments_completed']" icon="check" tone="emerald"
                  :hint="$kpis['appointments_confirmed'].' confirmés à venir'" />
    <x-admin.stat label="Annulations / absences" :value="$kpis['appointments_cancelled']" icon="x" tone="blush"
                  :hint="$kpis['appointments_total'] > 0 ? round($kpis['appointments_cancelled'] / $kpis['appointments_total'] * 100, 1).' % des RDV' : '—'" />
    <x-admin.stat label="Taux d'occupation" :value="$occupancy.' %'" icon="chart" tone="gold"
                  hint="capacité utilisée sur la période" />
</section>

{{-- Graphiques --}}
<section class="mt-6 grid gap-6 xl:grid-cols-3">
    <div class="card p-6 xl:col-span-2">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Évolution du chiffre d'affaires</h2>
        <p class="text-xs text-espresso-700/55">Prestations réservées et commandes boutique</p>
        <x-admin.bar-chart class="mt-6"
                           :rows="$series->map(fn ($r) => ['label' => $r['label'], 'value' => $r['services_revenue'], 'secondary' => $r['orders_revenue']])"
                           :legend="['Prestations', 'Boutique']" :height="220" />
    </div>

    <div class="card p-6">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Heures de pointe</h2>
        <p class="text-xs text-espresso-700/55">Répartition des rendez-vous par heure</p>
        <x-admin.bar-chart class="mt-6"
                           :rows="$peakHours->map(fn ($r) => ['label' => $r['hour'], 'value' => $r['count']])"
                           :money="false" :height="220" />
    </div>
</section>

<section class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="card p-6">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Prestations les plus rentables</h2>
        <div class="mt-5">
            <x-admin.rank-list :rows="$topServices" unit="RDV" />
        </div>
    </div>

    <div class="card p-6">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Produits les plus vendus</h2>
        <div class="mt-5">
            <x-admin.rank-list :rows="$topProducts" unit="vendus" />
        </div>
    </div>
</section>

<section class="card mt-6 p-6">
    <h2 class="font-display text-xl font-semibold text-espresso-900">Détail journalier</h2>
    <div class="mt-5 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="py-3 pr-4">Période</th>
                    <th class="py-3 pr-4 text-right">Rendez-vous</th>
                    <th class="py-3 pr-4 text-right">CA prestations</th>
                    <th class="py-3 pr-4 text-right">CA boutique</th>
                    <th class="py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @foreach ($series as $row)
                    <tr class="{{ $row['revenue'] == 0 ? 'text-espresso-700/40' : '' }}">
                        <td class="py-2.5 pr-4">{{ $row['period'] }}</td>
                        <td class="py-2.5 pr-4 text-right">{{ $row['appointments'] }}</td>
                        <td class="py-2.5 pr-4 text-right">{{ fcfa($row['services_revenue']) }}</td>
                        <td class="py-2.5 pr-4 text-right">{{ fcfa($row['orders_revenue']) }}</td>
                        <td class="py-2.5 text-right font-medium text-gold-600">{{ fcfa($row['revenue']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-espresso-900/10 text-sm font-medium">
                <tr>
                    <td class="py-3 pr-4">Total</td>
                    <td class="py-3 pr-4 text-right">{{ $series->sum('appointments') }}</td>
                    <td class="py-3 pr-4 text-right">{{ fcfa($series->sum('services_revenue')) }}</td>
                    <td class="py-3 pr-4 text-right">{{ fcfa($series->sum('orders_revenue')) }}</td>
                    <td class="py-3 text-right text-gold-600">{{ fcfa($series->sum('revenue')) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

@endsection
