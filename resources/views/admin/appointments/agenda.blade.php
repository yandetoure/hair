@extends('layouts.admin')

@section('title', 'Agenda')
@section('heading', 'Agenda')
@section('subheading', match ($view) {
    'jour' => ucfirst(fr_date($date)),
    'mois' => ucfirst($date->locale('fr')->isoFormat('MMMM YYYY')),
    default => 'Semaine du '.$from->locale('fr')->isoFormat('D MMM').' au '.$to->locale('fr')->isoFormat('D MMM YYYY'),
})

@php
    $prev = match ($view) {
        'jour' => $date->copy()->subDay(),
        'mois' => $date->copy()->subMonthNoOverflow(),
        default => $date->copy()->subWeek(),
    };
    $next = match ($view) {
        'jour' => $date->copy()->addDay(),
        'mois' => $date->copy()->addMonthNoOverflow(),
        default => $date->copy()->addWeek(),
    };
    $revenue = $appointments->whereIn('status', ['pending', 'confirmed', 'completed'])->sum('price');
@endphp

@section('actions')
    <div class="flex items-center gap-1 rounded-full border border-espresso-900/12 bg-white p-1">
        @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois'] as $key => $label)
            <a href="{{ route('admin.appointments.agenda', ['vue' => $key, 'date' => $date->toDateString()]) }}"
               class="rounded-full px-4 py-1.5 text-xs transition {{ $view === $key ? 'bg-espresso-900 text-cream-100' : 'text-espresso-700/70 hover:text-espresso-900' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
@endsection

@section('content')

<div class="card mb-6 flex flex-wrap items-center justify-between gap-4 p-4">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.appointments.agenda', ['vue' => $view, 'date' => $prev->toDateString()]) }}"
           class="grid h-9 w-9 place-items-center rounded-xl border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
            <x-icon name="chevron-left" class="h-4 w-4" />
        </a>
        <a href="{{ route('admin.appointments.agenda', ['vue' => $view]) }}"
           class="rounded-xl border border-espresso-900/12 px-4 py-2 text-xs transition hover:border-gold-500 hover:text-gold-600">
            Aujourd'hui
        </a>
        <a href="{{ route('admin.appointments.agenda', ['vue' => $view, 'date' => $next->toDateString()]) }}"
           class="grid h-9 w-9 place-items-center rounded-xl border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
            <x-icon name="chevron-right" class="h-4 w-4" />
        </a>
    </div>

    <div class="flex flex-wrap items-center gap-6 text-sm">
        <span class="text-espresso-700/60">{{ $appointments->count() }} rendez-vous</span>
        <span class="text-espresso-700/60">CA prévu : <span class="font-medium text-gold-600">{{ fcfa($revenue) }}</span></span>
        <form method="GET" class="flex items-center gap-2">
            <input type="hidden" name="vue" value="{{ $view }}">
            <input type="date" name="date" value="{{ $date->toDateString() }}"
                   class="rounded-xl border border-espresso-900/12 px-3 py-2 text-xs" onchange="this.form.submit()">
        </form>
    </div>
</div>

@if ($view === 'jour')
    @php $hours = config('salon.hours')[$date->isoWeekday()] ?? null; @endphp

    <div class="card p-6">
        @if (! $hours)
            <p class="py-10 text-center text-sm text-espresso-700/55">Le salon est fermé ce jour-là.</p>
        @elseif ($appointments->isEmpty())
            <p class="py-10 text-center text-sm text-espresso-700/55">Aucun rendez-vous pour cette journée.</p>
        @else
            <ul class="space-y-3">
                @foreach ($appointments as $appointment)
                    <li class="flex flex-col gap-4 rounded-xl border border-espresso-900/8 p-4 sm:flex-row sm:items-center">
                        <div class="w-20 shrink-0">
                            <span class="block font-display text-2xl font-semibold text-espresso-900">{{ $appointment->starts_at->format('H:i') }}</span>
                            <span class="block text-xs text-espresso-700/50">→ {{ $appointment->ends_at->format('H:i') }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-espresso-900">{{ $appointment->customer_name }}</p>
                            <p class="text-sm text-espresso-700/70">{{ $appointment->service?->name }} · {{ fcfa($appointment->price) }}</p>
                            <p class="mt-0.5 text-xs text-espresso-700/50">
                                {{ $appointment->customer_phone }}
                                @if ($appointment->staff) · {{ $appointment->staff->name }} @endif
                                @if ($appointment->notes) · « {{ $appointment->notes }} » @endif
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <span class="rounded-full px-3 py-1 text-xs font-medium ring-1 {{ $appointment->status_color }}">{{ $appointment->status_label }}</span>
                            <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="rounded-xl border border-espresso-900/12 px-3 py-1.5 text-xs">
                                    @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($appointment->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <a href="{{ route('admin.appointments.edit', $appointment) }}"
                               class="grid h-9 w-9 place-items-center rounded-xl border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
                                <x-icon name="edit" class="h-4 w-4" />
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

@elseif ($view === 'semaine')
    <div class="grid gap-3 md:grid-cols-7">
        @for ($day = $from->copy(); $day->lte($to); $day->addDay())
            @php
                $items = $byDay->get($day->toDateString(), collect());
                $isToday = $day->isToday();
                $closed = ! (config('salon.hours')[$day->isoWeekday()] ?? null);
            @endphp
            <div class="card flex min-h-[220px] flex-col p-3 {{ $isToday ? 'ring-2 ring-gold-500/40' : '' }} {{ $closed ? 'opacity-60' : '' }}">
                <div class="flex items-baseline justify-between border-b border-espresso-900/8 pb-2">
                    <span class="text-xs uppercase tracking-[0.14em] {{ $isToday ? 'text-gold-600' : 'text-espresso-700/55' }}">
                        {{ $day->locale('fr')->isoFormat('ddd') }}
                    </span>
                    <span class="font-display text-xl font-semibold text-espresso-900">{{ $day->format('d') }}</span>
                </div>

                <div class="mt-2 flex-1 space-y-1.5">
                    @forelse ($items as $appointment)
                        <a href="{{ route('admin.appointments.edit', $appointment) }}"
                           class="block rounded-lg border-l-2 bg-cream-50 p-2 text-[11px] transition hover:bg-cream-100
                                  {{ match ($appointment->status) {
                                        'confirmed' => 'border-emerald-400',
                                        'completed' => 'border-sky-400',
                                        'cancelled', 'no_show' => 'border-rose-300 opacity-60',
                                        default => 'border-amber-400',
                                     } }}">
                            <span class="block font-medium text-espresso-900">{{ $appointment->starts_at->format('H:i') }} · {{ \Illuminate\Support\Str::limit($appointment->customer_name, 14) }}</span>
                            <span class="block truncate text-espresso-700/60">{{ $appointment->service?->name }}</span>
                        </a>
                    @empty
                        <p class="pt-6 text-center text-[11px] text-espresso-700/35">{{ $closed ? 'Fermé' : '—' }}</p>
                    @endforelse
                </div>

                @if ($items->isNotEmpty())
                    <p class="mt-2 border-t border-espresso-900/8 pt-2 text-[11px] text-espresso-700/60">
                        {{ $items->count() }} RDV · <span class="text-gold-600">{{ fcfa($items->whereIn('status', ['pending', 'confirmed', 'completed'])->sum('price')) }}</span>
                    </p>
                @endif
            </div>
        @endfor
    </div>

@else
    <div class="card overflow-hidden">
        <div class="grid grid-cols-7 border-b border-espresso-900/8 bg-cream-50 text-center text-xs uppercase tracking-[0.14em] text-espresso-700/55">
            @foreach (['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $label)
                <span class="py-3">{{ $label }}</span>
            @endforeach
        </div>

        <div class="grid grid-cols-7">
            @for ($day = $from->copy(); $day->lte($to); $day->addDay())
                @php
                    $items = $byDay->get($day->toDateString(), collect());
                    $inMonth = $day->month === $date->month;
                    $dayRevenue = $items->whereIn('status', ['pending', 'confirmed', 'completed'])->sum('price');
                @endphp
                <div class="min-h-[110px] border-b border-r border-espresso-900/8 p-2 {{ $inMonth ? '' : 'bg-cream-50/60 opacity-50' }} {{ $day->isToday() ? 'bg-gold-500/8' : '' }}">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.appointments.agenda', ['vue' => 'jour', 'date' => $day->toDateString()]) }}"
                           class="text-sm font-medium {{ $day->isToday() ? 'text-gold-600' : 'text-espresso-900' }} hover:underline">
                            {{ $day->format('j') }}
                        </a>
                        @if ($items->isNotEmpty())
                            <span class="rounded-full bg-espresso-900/8 px-1.5 text-[10px] text-espresso-700/70">{{ $items->count() }}</span>
                        @endif
                    </div>

                    <div class="mt-1 space-y-1">
                        @foreach ($items->take(3) as $appointment)
                            <p class="truncate text-[10px] text-espresso-700/70">
                                <span class="text-espresso-900">{{ $appointment->starts_at->format('H:i') }}</span>
                                {{ \Illuminate\Support\Str::limit($appointment->customer_name, 12) }}
                            </p>
                        @endforeach
                        @if ($items->count() > 3)
                            <p class="text-[10px] text-gold-600">+{{ $items->count() - 3 }} autres</p>
                        @endif
                    </div>

                    @if ($dayRevenue > 0)
                        <p class="mt-1 text-[10px] font-medium text-gold-600">{{ number_format($dayRevenue / 1000, 0, ',', ' ') }}k</p>
                    @endif
                </div>
            @endfor
        </div>
    </div>
@endif

@endsection
