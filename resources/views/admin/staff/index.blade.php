@extends('layouts.admin')

@section('title', 'Équipe')
@section('heading', 'Équipe du salon')
@section('subheading', $members->count().' membres')

@section('actions')
    <a href="{{ route('admin.staff.create') }}" class="btn-gold py-2.5 text-xs">
        <x-icon name="plus" class="h-4 w-4" /> Ajouter un membre
    </a>
@endsection

@section('content')

<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($members as $member)
        <div class="card overflow-hidden">
            @if ($member->photo)
                <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}" class="aspect-[4/3] w-full object-cover">
            @else
                <div class="texture-gradient grid aspect-[4/3] w-full place-items-center bg-espresso-900">
                    <span class="font-display text-4xl font-semibold text-gold-300">{{ $member->initials }}</span>
                </div>
            @endif

            <div class="p-5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-espresso-900">{{ $member->name }}</h2>
                        <p class="text-xs uppercase tracking-[0.14em] text-gold-600">{{ $member->role }}</p>
                    </div>
                    <span class="rounded-full px-2.5 py-0.5 text-[11px] ring-1 {{ $member->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                        {{ $member->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <p class="mt-3 text-sm leading-relaxed text-espresso-700/75">{{ $member->bio }}</p>
                <p class="mt-3 text-xs text-espresso-700/50">{{ $member->appointments()->count() }} rendez-vous assurés</p>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.staff.edit', $member) }}" class="btn-outline flex-1 py-2 text-xs">Modifier</a>
                    <form method="POST" action="{{ route('admin.staff.destroy', $member) }}"
                          onsubmit="return confirm('Retirer ce membre de l\'équipe ?')">
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
            Aucun membre enregistré.
        </p>
    @endforelse
</div>

@endsection
