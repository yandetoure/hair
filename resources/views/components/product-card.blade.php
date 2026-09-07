@props(['product'])

<article class="card group flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_60px_-30px_rgba(51,34,28,0.45)]">
    <a href="{{ route('shop.show', $product) }}" class="block">
        <x-media :src="$product->image" :alt="$product->name" icon="box" ratio="aspect-square"
                 class="rounded-none rounded-t-2xl" />
    </a>

    <div class="flex flex-1 flex-col p-5">
        <span class="eyebrow">{{ $product->category }}</span>
        <h3 class="mt-1.5 font-display text-xl font-semibold leading-snug text-espresso-900">
            <a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a>
        </h3>

        <div class="mt-auto flex items-end justify-between pt-4">
            <div>
                <span class="block font-display text-lg font-semibold text-gold-600">{{ fcfa($product->price) }}</span>
                <span class="text-xs {{ $product->in_stock ? 'text-emerald-600' : 'text-rose-500' }}">
                    {{ $product->in_stock ? 'En stock' : 'Rupture' }}
                </span>
            </div>

            @if ($product->in_stock)
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button class="grid h-10 w-10 place-items-center rounded-full bg-espresso-900 text-cream-100 transition hover:bg-gold-500"
                            aria-label="Ajouter au panier">
                        <x-icon name="plus" class="h-4 w-4" />
                    </button>
                </form>
            @endif
        </div>
    </div>
</article>
