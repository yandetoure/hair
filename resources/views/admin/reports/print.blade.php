<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport {{ $from->format('d/m/Y') }} – {{ $to->format('d/m/Y') }} — {{ config('salon.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white">

<div class="mx-auto max-w-4xl p-10">
    <div class="no-print mb-8 flex justify-end gap-3">
        <button onclick="window.print()" class="btn-dark py-2.5 text-xs">Imprimer / enregistrer en PDF</button>
        <a href="{{ url()->previous() }}" class="btn-outline py-2.5 text-xs">Retour</a>
    </div>

    <header class="flex items-start justify-between border-b-2 border-espresso-900 pb-6">
        <div>
            <p class="font-display text-3xl font-semibold text-espresso-900">{{ config('salon.name') }}</p>
            <p class="text-sm text-espresso-700/70">{{ config('salon.address') }} · {{ config('salon.phone') }}</p>
        </div>
        <div class="text-right text-sm">
            <p class="font-medium text-espresso-900">Rapport d'activité</p>
            <p class="text-espresso-700/70">Du {{ $from->format('d/m/Y') }} au {{ $to->format('d/m/Y') }}</p>
            <p class="text-xs text-espresso-700/50">Édité le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </header>

    <section class="mt-8">
        <h2 class="text-xs uppercase tracking-[0.2em] text-espresso-700/55">Synthèse</h2>
        <div class="mt-4 grid grid-cols-3 gap-4">
            @foreach ([
                ["Chiffre d'affaires total", fcfa($kpis['revenue_total'])],
                ['CA prestations', fcfa($kpis['revenue_services_expected'])],
                ['CA boutique', fcfa($kpis['revenue_products'])],
                ['Rendez-vous', $kpis['appointments_total']],
                ['Prestations réalisées', $kpis['appointments_completed']],
                ['Annulations / absences', $kpis['appointments_cancelled']],
                ['Commandes', $kpis['orders_total']],
                ['Ticket moyen', fcfa($kpis['ticket_moyen'])],
                ["Taux d'occupation", $occupancy.' %'],
            ] as [$label, $value])
                <div class="rounded-lg border border-espresso-900/15 p-4">
                    <p class="text-[10px] uppercase tracking-[0.14em] text-espresso-700/55">{{ $label }}</p>
                    <p class="mt-1 font-display text-2xl font-semibold text-espresso-900">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-xs uppercase tracking-[0.2em] text-espresso-700/55">Détail par période</h2>
        <table class="mt-4 w-full text-sm">
            <thead class="border-b border-espresso-900/20 text-left text-xs uppercase tracking-[0.1em] text-espresso-700/55">
                <tr>
                    <th class="py-2 pr-4">Période</th>
                    <th class="py-2 pr-4 text-right">RDV</th>
                    <th class="py-2 pr-4 text-right">CA prestations</th>
                    <th class="py-2 pr-4 text-right">CA boutique</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/8">
                @foreach ($series as $row)
                    <tr>
                        <td class="py-1.5 pr-4">{{ $row['key'] }}</td>
                        <td class="py-1.5 pr-4 text-right">{{ $row['appointments'] }}</td>
                        <td class="py-1.5 pr-4 text-right">{{ fcfa($row['services_revenue']) }}</td>
                        <td class="py-1.5 pr-4 text-right">{{ fcfa($row['orders_revenue']) }}</td>
                        <td class="py-1.5 text-right font-medium">{{ fcfa($row['revenue']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-espresso-900 font-medium">
                <tr>
                    <td class="py-2 pr-4">Total</td>
                    <td class="py-2 pr-4 text-right">{{ $series->sum('appointments') }}</td>
                    <td class="py-2 pr-4 text-right">{{ fcfa($series->sum('services_revenue')) }}</td>
                    <td class="py-2 pr-4 text-right">{{ fcfa($series->sum('orders_revenue')) }}</td>
                    <td class="py-2 text-right">{{ fcfa($series->sum('revenue')) }}</td>
                </tr>
            </tfoot>
        </table>
    </section>

    <section class="mt-10 grid grid-cols-2 gap-10">
        <div>
            <h2 class="text-xs uppercase tracking-[0.2em] text-espresso-700/55">Top prestations</h2>
            <table class="mt-4 w-full text-sm">
                <tbody class="divide-y divide-espresso-900/8">
                    @forelse ($topServices as $row)
                        <tr>
                            <td class="py-1.5">{{ $row['name'] }}</td>
                            <td class="py-1.5 text-right text-espresso-700/70">{{ $row['count'] }}</td>
                            <td class="py-1.5 text-right font-medium">{{ fcfa($row['revenue']) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-3 text-espresso-700/50">Aucune donnée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <h2 class="text-xs uppercase tracking-[0.2em] text-espresso-700/55">Top produits</h2>
            <table class="mt-4 w-full text-sm">
                <tbody class="divide-y divide-espresso-900/8">
                    @forelse ($topProducts as $row)
                        <tr>
                            <td class="py-1.5">{{ $row['name'] }}</td>
                            <td class="py-1.5 text-right text-espresso-700/70">{{ $row['count'] }}</td>
                            <td class="py-1.5 text-right font-medium">{{ fcfa($row['revenue']) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-3 text-espresso-700/50">Aucune donnée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <footer class="mt-12 border-t border-espresso-900/15 pt-4 text-xs text-espresso-700/50">
        Rapport généré automatiquement par l'espace de gestion {{ config('salon.name') }}.
    </footer>
</div>

</body>
</html>
