@extends('layouts.admin')

@section('title', 'Galerie')
@section('heading', 'Galerie des réalisations')
@section('subheading', $items->total().' visuels publiés sur le site')

@section('actions')
    <a href="{{ route('admin.gallery.create') }}" class="btn-gold py-2.5 text-xs">
        <x-icon name="plus" class="h-4 w-4" /> Ajouter une réalisation
    </a>
@endsection

@section('content')

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($items as $item)
        <div class="card overflow-hidden">
            <div class="relative">
                <img src="{{ salon_image($item->image) }}" alt="{{ $item->title }}" class="aspect-[4/3] w-full object-cover">
                @unless ($item->is_published)
                    <span class="absolute left-3 top-3 rounded-full bg-slate-900/80 px-3 py-1 text-[11px] text-white">Masquée</span>
                @endunless
            </div>

            <div class="p-4">
                <span class="text-[10px] uppercase tracking-[0.18em] text-gold-600">{{ $item->category }}</span>
                <h2 class="mt-1 font-display text-lg font-semibold leading-snug text-espresso-900">{{ $item->title }}</h2>
                <p class="mt-1 line-clamp-2 text-xs text-espresso-700/60">{{ $item->description }}</p>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.gallery.edit', $item) }}" class="btn-outline flex-1 py-2 text-xs">Modifier</a>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}"
                          onsubmit="return confirm('Supprimer cette réalisation ?')">
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
            Aucune réalisation. Ajoutez vos plus belles photos pour alimenter la galerie du site.
        </p>
    @endforelse
</div>

<div class="mt-6">{{ $items->links() }}</div>

@endsection
