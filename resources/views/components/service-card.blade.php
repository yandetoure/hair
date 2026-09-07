@props(['service'])

<article class="card group flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_60px_-30px_rgba(51,34,28,0.45)]">
    <x-media :src="$service->image" :alt="$service->name" :icon="$service->category?->icon ?? 'sparkles'"
             ratio="aspect-[5/3]" class="rounded-none rounded-t-2xl" />

    <div class="flex flex-1 flex-col p-6">
        <span class="eyebrow">{{ $service->category?->name }}</span>
        <h3 class="mt-2 font-display text-2xl font-semibold text-espresso-900">{{ $service->name }}</h3>
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-espresso-700/80">{{ $service->description }}</p>

        <div class="mt-5 flex items-center justify-between border-t border-espresso-900/10 pt-4">
            <div>
                <span class="block font-display text-xl font-semibold text-gold-600">{{ $service->price_label }}</span>
                <span class="flex items-center gap-1 text-xs text-espresso-700/60">
                    <x-icon name="clock" class="h-3.5 w-3.5" /> {{ $service->duration_label }}
                </span>
            </div>
            <a href="{{ route('booking.create', ['service' => $service->slug]) }}"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-espresso-900 transition group-hover:text-gold-600">
                Réserver <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</article>
