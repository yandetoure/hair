@extends('layouts.admin')

@section('title', 'Forfaits')
@section('heading', 'Forfaits & rituels')
@section('subheading', $offers->count().' formules proposées')

@section('actions')
    <a href="{{ route('admin.offers.create') }}" class="btn-gold py-2.5 text-xs">
        <x-icon name="plus" class="h-4 w-4" /> Nouveau forfait
    </a>
@endsection

@section('content')

<div class="grid gap-6 lg:grid-cols-3">
    @forelse ($offers as $offer)
        <div class="card overflow-hidden">
            @if ($offer->image)
                <img src="{{ salon_image($offer->image) }}" alt="{{ $offer->name }}" class="aspect-[3/2] w-full object-cover">
            @else
                <div class="texture-gradient aspect-[3/2] w-full"></div>
            @endif

            <div class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-[10px] uppercase tracking-[0.18em] text-gold-600">{{ $offer->tagline }}</span>
                        <h2 class="mt-1 font-display text-xl font-semibold text-espresso-900">{{ $offer->name }}</h2>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-0.5 text-[11px] ring-1 {{ $offer->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                        {{ $offer->is_active ? 'En ligne' : 'Masqué' }}
                    </span>
                </div>

                <p class="mt-3 line-clamp-2 text-sm text-espresso-700/75">{{ $offer->description }}</p>

                <div class="mt-4 flex items-baseline gap-3">
                    <span class="font-display text-2xl font-semibold text-gold-600">{{ fcfa($offer->price) }}</span>
                    @if ($offer->compare_price)
                        <span class="text-xs text-espresso-700/45 line-through">{{ fcfa($offer->compare_price) }}</span>
                    @endif
                </div>
                <p class="mt-1 text-xs text-espresso-700/55">
                    {{ $offer->lines->count() }} prestations incluses
                    @if ($offer->duration_label) · {{ $offer->duration_label }} @endif
                </p>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.offers.edit', $offer) }}" class="btn-outline flex-1 py-2 text-xs">Modifier</a>
                    <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}"
                          onsubmit="return confirm('Supprimer ce forfait ?')">
                        @csrf @method('DELETE')
                        <button class="grid h-9 w-9 place-items-center rounded-full border border-espresso-900/12 text-rose-500 transition hover:border-rose-300">
                            <x-icon name="trash" class="h-4 w-4" />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-full rounded-2xl border border-dashed border-espresso-900/15 py-16 text-center text-espresso-700/50">
            Aucun forfait pour le moment.
        </p>
    @endforelse
</div>

@endsection
