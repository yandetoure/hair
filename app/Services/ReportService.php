<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /** Indicateurs clés d'une période. */
    public function kpis(Carbon $from, Carbon $to): array
    {
        $appointments = Appointment::whereBetween('starts_at', [$from, $to])->get();
        $orders = Order::whereBetween('created_at', [$from, $to])->get();

        $active = $appointments->whereIn('status', ['pending', 'confirmed', 'completed']);
        $done = $appointments->where('status', 'completed');
        $validOrders = $orders->where('status', '!=', 'cancelled');

        return [
            'appointments_total' => $appointments->count(),
            'appointments_pending' => $appointments->where('status', 'pending')->count(),
            'appointments_confirmed' => $appointments->where('status', 'confirmed')->count(),
            'appointments_completed' => $done->count(),
            'appointments_cancelled' => $appointments->whereIn('status', ['cancelled', 'no_show'])->count(),
            'revenue_services_expected' => (int) $active->sum('price'),
            'revenue_services_done' => (int) $done->sum('price'),
            'orders_total' => $validOrders->count(),
            'orders_pending' => $orders->where('status', 'pending')->count(),
            'revenue_products' => (int) $validOrders->sum('total'),
            'revenue_total' => (int) $active->sum('price') + (int) $validOrders->sum('total'),
            'ticket_moyen' => $active->count() + $validOrders->count() > 0
                ? (int) round(((int) $active->sum('price') + (int) $validOrders->sum('total')) / ($active->count() + $validOrders->count()))
                : 0,
            'clients' => $appointments->pluck('customer_phone')
                ->merge($orders->pluck('customer_phone'))->unique()->count(),
        ];
    }

    /** Série journalière (ou mensuelle) pour les graphiques. */
    public function series(Carbon $from, Carbon $to, string $unit = 'day'): Collection
    {
        $format = $unit === 'month' ? 'Y-m' : 'Y-m-d';

        $appointments = Appointment::active()
            ->whereBetween('starts_at', [$from, $to])
            ->get(['starts_at', 'price'])
            ->groupBy(fn ($a) => $a->starts_at->format($format));

        $orders = Order::paid()
            ->whereBetween('created_at', [$from, $to])
            ->get(['created_at', 'total'])
            ->groupBy(fn ($o) => $o->created_at->format($format));

        $buckets = collect();
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            $key = $cursor->format($format);
            $buckets->put($key, [
                'key' => $key,
                'label' => $unit === 'month'
                    ? $cursor->locale('fr')->isoFormat('MMM')
                    : $cursor->locale('fr')->isoFormat('DD/MM'),
                'short' => $unit === 'month'
                    ? $cursor->locale('fr')->isoFormat('MMM')
                    : $cursor->locale('fr')->isoFormat('ddd'),
                'period' => $unit === 'month'
                    ? ucfirst($cursor->locale('fr')->isoFormat('MMMM YYYY'))
                    : ucfirst($cursor->locale('fr')->isoFormat('ddd D MMMM')),
                'appointments' => $appointments->get($key)?->count() ?? 0,
                'services_revenue' => (int) ($appointments->get($key)?->sum('price') ?? 0),
                'orders_revenue' => (int) ($orders->get($key)?->sum('total') ?? 0),
            ]);

            $unit === 'month' ? $cursor->addMonth() : $cursor->addDay();
        }

        return $buckets->values()->map(function ($row) {
            $row['revenue'] = $row['services_revenue'] + $row['orders_revenue'];

            return $row;
        });
    }

    public function topServices(Carbon $from, Carbon $to, int $limit = 6): Collection
    {
        return Appointment::active()
            ->with('service:id,name')
            ->whereBetween('starts_at', [$from, $to])
            ->get(['service_id', 'price'])
            ->groupBy('service_id')
            ->map(fn ($rows) => [
                'name' => $rows->first()->service?->name ?? 'Service supprimé',
                'count' => $rows->count(),
                'revenue' => (int) $rows->sum('price'),
            ])
            ->sortByDesc('revenue')
            ->take($limit)
            ->values();
    }

    public function topProducts(Carbon $from, Carbon $to, int $limit = 6): Collection
    {
        return OrderItem::query()
            ->whereHas('order', fn ($q) => $q->paid()->whereBetween('created_at', [$from, $to]))
            ->get()
            ->groupBy('name')
            ->map(fn ($rows, $name) => [
                'name' => $name,
                'count' => (int) $rows->sum('quantity'),
                'revenue' => (int) $rows->sum('line_total'),
            ])
            ->sortByDesc('revenue')
            ->take($limit)
            ->values();
    }

    /** Taux d'occupation du salon sur la période (minutes réservées / capacité). */
    public function occupancy(Carbon $from, Carbon $to): float
    {
        $capacity = (int) config('salon.capacity');
        $hours = config('salon.hours');
        $openMinutes = 0;
        $cursor = $from->copy()->startOfDay();

        while ($cursor->lte($to)) {
            if ($range = $hours[(int) $cursor->isoWeekday()] ?? null) {
                $openMinutes += Carbon::parse($range[0])->diffInMinutes(Carbon::parse($range[1]));
            }
            $cursor->addDay();
        }

        if ($openMinutes === 0) {
            return 0.0;
        }

        $booked = Appointment::active()
            ->whereBetween('starts_at', [$from, $to])
            ->with('service:id,duration')
            ->get()
            ->sum(fn ($a) => $a->starts_at->diffInMinutes($a->ends_at));

        return round(min(100, $booked / ($openMinutes * $capacity) * 100), 1);
    }

    /** Répartition horaire des RDV (heures de pointe). */
    public function peakHours(Carbon $from, Carbon $to): Collection
    {
        $rows = Appointment::active()
            ->whereBetween('starts_at', [$from, $to])
            ->get(['starts_at'])
            ->groupBy(fn ($a) => (int) $a->starts_at->format('H'));

        return collect(range(8, 20))->map(fn ($h) => [
            'hour' => sprintf('%02dh', $h),
            'count' => $rows->get($h)?->count() ?? 0,
        ]);
    }
}
