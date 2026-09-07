<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reports)
    {
        return view('admin.reports.index', $this->data($request, $reports));
    }

    public function print(Request $request, ReportService $reports)
    {
        return view('admin.reports.print', $this->data($request, $reports));
    }

    public function export(Request $request, ReportService $reports): StreamedResponse
    {
        [$from, $to, $period] = $this->period($request);
        $kpis = $reports->kpis($from, $to);
        $filename = 'rapport-'.$from->format('Y-m-d').'_'.$to->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($from, $to, $kpis, $reports) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM pour Excel

            fputcsv($out, ['Rapport '.config('salon.name')], ';');
            fputcsv($out, ['Période', $from->format('d/m/Y'), 'au', $to->format('d/m/Y')], ';');
            fputcsv($out, [], ';');

            fputcsv($out, ['SYNTHÈSE'], ';');
            foreach ([
                'Rendez-vous (total)' => $kpis['appointments_total'],
                'Rendez-vous confirmés' => $kpis['appointments_confirmed'],
                'Rendez-vous terminés' => $kpis['appointments_completed'],
                'Rendez-vous annulés' => $kpis['appointments_cancelled'],
                'CA prestations (prévu)' => $kpis['revenue_services_expected'],
                'CA prestations (réalisé)' => $kpis['revenue_services_done'],
                'Commandes' => $kpis['orders_total'],
                'CA boutique' => $kpis['revenue_products'],
                'CA total' => $kpis['revenue_total'],
                'Ticket moyen' => $kpis['ticket_moyen'],
                'Clients uniques' => $kpis['clients'],
                "Taux d'occupation (%)" => $reports->occupancy($from, $to),
            ] as $label => $value) {
                fputcsv($out, [$label, $value], ';');
            }

            fputcsv($out, [], ';');
            fputcsv($out, ['TOP PRESTATIONS', 'Nombre', 'CA'], ';');
            foreach ($reports->topServices($from, $to, 20) as $row) {
                fputcsv($out, [$row['name'], $row['count'], $row['revenue']], ';');
            }

            fputcsv($out, [], ';');
            fputcsv($out, ['TOP PRODUITS', 'Quantité', 'CA'], ';');
            foreach ($reports->topProducts($from, $to, 20) as $row) {
                fputcsv($out, [$row['name'], $row['count'], $row['revenue']], ';');
            }

            fputcsv($out, [], ';');
            fputcsv($out, ['DÉTAIL DES RENDEZ-VOUS'], ';');
            fputcsv($out, ['Référence', 'Date', 'Heure', 'Client', 'Téléphone', 'Prestation', 'Coiffeuse', 'Statut', 'Montant'], ';');
            Appointment::with('service', 'staff')
                ->whereBetween('starts_at', [$from, $to])
                ->orderBy('starts_at')
                ->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $a) {
                        fputcsv($out, [
                            $a->reference,
                            $a->starts_at->format('d/m/Y'),
                            $a->starts_at->format('H:i'),
                            $a->customer_name,
                            $a->customer_phone,
                            $a->service?->name,
                            $a->staff?->name ?? '—',
                            $a->status_label,
                            $a->price,
                        ], ';');
                    }
                });

            fputcsv($out, [], ';');
            fputcsv($out, ['DÉTAIL DES COMMANDES'], ';');
            fputcsv($out, ['Référence', 'Date', 'Client', 'Téléphone', 'Articles', 'Statut', 'Total'], ';');
            Order::with('items')
                ->whereBetween('created_at', [$from, $to])
                ->orderBy('created_at')
                ->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $o) {
                        fputcsv($out, [
                            $o->reference,
                            $o->created_at->format('d/m/Y'),
                            $o->customer_name,
                            $o->customer_phone,
                            $o->items->sum('quantity'),
                            $o->status_label,
                            $o->total,
                        ], ';');
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** @return array{0: Carbon, 1: Carbon, 2: string} */
    private function period(Request $request): array
    {
        $period = $request->query('periode', 'mois');

        return match ($period) {
            'jour' => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay(), 'jour'],
            'semaine' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), 'semaine'],
            'trimestre' => [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter(), 'trimestre'],
            'annee' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear(), 'annee'],
            'perso' => [
                $request->filled('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::now()->startOfMonth(),
                $request->filled('to') ? Carbon::parse($request->query('to'))->endOfDay() : Carbon::now()->endOfDay(),
                'perso',
            ],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), 'mois'],
        };
    }

    private function data(Request $request, ReportService $reports): array
    {
        [$from, $to, $period] = $this->period($request);
        $days = max(1, (int) $from->diffInDays($to) + 1);
        $unit = $days > 92 ? 'month' : 'day';

        $previousFrom = $from->copy()->subDays($days);
        $previousTo = $from->copy()->subSecond();

        return [
            'from' => $from,
            'to' => $to,
            'period' => $period,
            'kpis' => $reports->kpis($from, $to),
            'previous' => $reports->kpis($previousFrom, $previousTo),
            'series' => $reports->series($from, $to, $unit),
            'topServices' => $reports->topServices($from, $to, 8),
            'topProducts' => $reports->topProducts($from, $to, 8),
            'occupancy' => $reports->occupancy($from, $to),
            'peakHours' => $reports->peakHours($from, $to),
            'query' => $request->query(),
        ];
    }
}
