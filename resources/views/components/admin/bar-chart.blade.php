@props([
    'rows' => [],          // [['label' =>, 'value' =>, 'secondary' => ?], ...]
    'height' => 180,
    'money' => true,
    'legend' => null,      // ['Prestations', 'Boutique']
])

@php
    $rows = collect($rows);
    $max = max(1, $rows->max(fn ($r) => ($r['value'] ?? 0) + ($r['secondary'] ?? 0)));
@endphp

<div {{ $attributes }}>
    @if ($legend)
        <div class="mb-4 flex flex-wrap items-center gap-4 text-xs text-espresso-700/60">
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-sm bg-gold-500"></span>{{ $legend[0] }}</span>
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-sm bg-espresso-900/70"></span>{{ $legend[1] }}</span>
        </div>
    @endif

    <div class="flex items-end gap-1.5 overflow-x-auto" style="height: {{ $height }}px">
        @foreach ($rows as $row)
            @php
                $total = ($row['value'] ?? 0) + ($row['secondary'] ?? 0);
                $ratio = $total / $max;
                $tooltip = $row['label'].' : '.($money ? fcfa($total) : $total);
            @endphp
            <div class="group flex h-full min-w-6 flex-1 flex-col justify-end" title="{{ $tooltip }}">
                <span class="mb-1 text-center text-[10px] font-medium text-espresso-700/0 transition group-hover:text-espresso-700/80">
                    {{ $money ? number_format($total / 1000, 0, ',', ' ').'k' : $total }}
                </span>
                <div class="flex w-full flex-col justify-end rounded-t-md" style="height: {{ max(2, $ratio * 100) }}%">
                    @if (($row['secondary'] ?? 0) > 0)
                        <div class="w-full rounded-t-md bg-espresso-900/70 transition group-hover:bg-espresso-900"
                             style="height: {{ $total > 0 ? ($row['secondary'] / $total) * 100 : 0 }}%"></div>
                    @endif
                    <div class="w-full bg-gold-500 transition group-hover:bg-gold-600 {{ ($row['secondary'] ?? 0) > 0 ? '' : 'rounded-t-md' }}"
                         style="height: {{ $total > 0 ? (($row['value'] ?? 0) / $total) * 100 : 100 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-2 flex gap-1.5 overflow-x-auto text-center text-[10px] uppercase tracking-wider text-espresso-700/45">
        @foreach ($rows as $row)
            <span class="min-w-6 flex-1">{{ $row['label'] }}</span>
        @endforeach
    </div>
</div>
