@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'align' => 'center',
])

<div {{ $attributes->merge(['class' => 'max-w-2xl '.($align === 'center' ? 'mx-auto text-center' : '')]) }}>
    @if ($eyebrow)
        <span class="eyebrow">{{ $eyebrow }}</span>
    @endif
    <h2 class="mt-3 font-display text-4xl font-semibold leading-tight text-espresso-900 sm:text-5xl">{{ $title }}</h2>
    @if ($subtitle)
        <p class="mt-4 text-base leading-relaxed text-espresso-700/80">{{ $subtitle }}</p>
    @endif
    <span class="mt-6 inline-block h-px w-16 bg-gold-500 {{ $align === 'center' ? '' : 'ml-0' }}"></span>
</div>
