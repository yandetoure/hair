@props([
    'src' => null,
    'alt' => '',
    'icon' => 'sparkles',
    'ratio' => 'aspect-[4/3]',
    'overlay' => false,
])

@php $url = salon_image($src); @endphp

<div {{ $attributes->merge(['class' => $ratio.' group/media relative overflow-hidden rounded-2xl bg-cream-200']) }}>
    @if ($url)
        <img src="{{ $url }}" alt="{{ $alt }}" loading="lazy"
             class="h-full w-full object-cover transition duration-700 group-hover/media:scale-105">
        @if ($overlay)
            <div class="absolute inset-0 bg-gradient-to-t from-espresso-900/70 via-espresso-900/10 to-transparent"></div>
        @endif
    @else
        <div class="texture-gradient absolute inset-0"></div>
        <div class="pattern-dots absolute inset-0 opacity-60"></div>
        <div class="absolute inset-0 grid place-items-center">
            <x-icon :name="$icon" class="h-12 w-12 text-espresso-900/25" />
        </div>
    @endif

    {{ $slot }}
</div>
