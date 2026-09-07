@props([
    'rows' => [],       // [['name' =>, 'count' =>, 'revenue' =>], ...]
    'unit' => 'RDV',
])

@php
    $rows = collect($rows);
    $max = max(1, $rows->max('revenue') ?? 0);
@endphp

@if ($rows->isEmpty())
    <p class="py-8 text-center text-sm text-espresso-700/50">Aucune donnée sur la période.</p>
@else
    <ul class="space-y-4">
        @foreach ($rows as $row)
            <li>
                <div class="flex items-baseline justify-between gap-4 text-sm">
                    <span class="truncate font-medium text-espresso-900">{{ $row['name'] }}</span>
                    <span class="whitespace-nowrap text-espresso-700/60">
                        {{ $row['count'] }} {{ $unit }} · <span class="font-medium text-gold-600">{{ fcfa($row['revenue']) }}</span>
                    </span>
                </div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-cream-200">
                    <div class="h-full rounded-full bg-gold-500" style="width: {{ max(3, $row['revenue'] / $max * 100) }}%"></div>
                </div>
            </li>
        @endforeach
    </ul>
@endif
