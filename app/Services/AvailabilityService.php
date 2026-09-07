<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Carbon;

class AvailabilityService
{
    /**
     * Créneaux d'une journée pour un service donné.
     *
     * @return array<int, array{time: string, available: bool, reason: ?string}>
     */
    public function slots(Carbon $date, Service $service): array
    {
        $hours = config('salon.hours')[(int) $date->isoWeekday()] ?? null;

        if (! $hours) {
            return [];
        }

        $interval = (int) config('salon.slot_interval');
        $capacity = (int) config('salon.capacity');
        $opening = $date->copy()->setTimeFromTimeString($hours[0]);
        $closing = $date->copy()->setTimeFromTimeString($hours[1]);
        $notBefore = now()->addHours((int) config('salon.min_notice_hours'));

        $booked = Appointment::query()
            ->active()
            ->whereDate('starts_at', $date->toDateString())
            ->get(['starts_at', 'ends_at']);

        $slots = [];

        for ($cursor = $opening->copy(); $cursor->lt($closing); $cursor->addMinutes($interval)) {
            $end = $cursor->copy()->addMinutes($service->duration);

            if ($end->gt($closing)) {
                break;
            }

            $overlapping = $booked->filter(
                fn ($a) => $a->starts_at->lt($end) && $a->ends_at->gt($cursor)
            )->count();

            $reason = null;
            if ($cursor->lt($notBefore)) {
                $reason = 'passé';
            } elseif ($overlapping >= $capacity) {
                $reason = 'complet';
            }

            $slots[] = [
                'time' => $cursor->format('H:i'),
                'available' => $reason === null,
                'reason' => $reason,
            ];
        }

        return $slots;
    }

    public function isSlotAvailable(Carbon $start, Service $service): bool
    {
        return collect($this->slots($start->copy()->startOfDay(), $service))
            ->firstWhere('time', $start->format('H:i'))['available'] ?? false;
    }

    /** Jours ouvrés réservables à partir d'aujourd'hui. */
    public function openDays(int $limit = 21): array
    {
        $days = [];
        $cursor = now()->startOfDay();
        $max = now()->addDays((int) config('salon.max_days_ahead'));

        while (count($days) < $limit && $cursor->lte($max)) {
            if (config('salon.hours')[(int) $cursor->isoWeekday()] ?? null) {
                $days[] = $cursor->copy();
            }
            $cursor->addDay();
        }

        return $days;
    }
}
