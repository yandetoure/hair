@props([
    'src' => null,
    'alt' => '',
    'icon' => 'sparkles',
    'ratio' => 'aspect-[4/3]',
])

<div {{ $attributes->merge(['class' => $ratio.' relative overflow-hidden rounded-2xl bg-cream-200']) }}>
    @if ($src)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($src) }}" alt="{{ $alt }}"
             class="h-full w-full object-cover transition duration-700 hover:scale-105" loading="lazy">
    @else
        <div class="texture-gradient absolute inset-0"></div>
        <div class="pattern-dots absolute inset-0 opacity-60"></div>
        <div class="absolute inset-0 grid place-items-center">
            <x-icon :name="$icon" class="h-12 w-12 text-espresso-900/25" />
        </div>
    @endif
</div>
