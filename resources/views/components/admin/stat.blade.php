@props([
    'label' => '',
    'value' => '',
    'hint' => null,
    'icon' => 'chart',
    'trend' => null,
    'tone' => 'gold',
])

@php
    $tones = [
        'gold' => 'bg-gold-500/15 text-gold-600',
        'blush' => 'bg-blush-500/15 text-blush-500',
        'emerald' => 'bg-emerald-500/12 text-emerald-600',
        'sky' => 'bg-sky-500/12 text-sky-600',
        'espresso' => 'bg-espresso-900/8 text-espresso-900',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'card p-5']) }}>
    <div class="flex items-start justify-between gap-3">
        <span class="text-xs uppercase tracking-[0.14em] text-espresso-700/55">{{ $label }}</span>
        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl {{ $tones[$tone] ?? $tones['gold'] }}">
            <x-icon :name="$icon" class="h-4 w-4" />
        </span>
    </div>

    <p class="mt-3 font-display text-3xl font-semibold leading-none text-espresso-900">{{ $value }}</p>

    <div class="mt-2 flex items-center gap-2 text-xs">
        @if (! is_null($trend))
            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 font-medium {{ $trend >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                {{ $trend >= 0 ? '▲' : '▼' }} {{ number_format(abs($trend), 1, ',', ' ') }} %
            </span>
        @endif
        @if ($hint)
            <span class="text-espresso-700/55">{{ $hint }}</span>
        @endif
    </div>
</div>
