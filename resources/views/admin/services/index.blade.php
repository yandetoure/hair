@extends('layouts.admin')

@section('title', 'Prestations')
@section('heading', 'Prestations & tarifs')
@section('subheading', $services->total().' prestations au catalogue')

@section('actions')
    <a href="{{ route('admin.services.create') }}" class="btn-gold py-2.5 text-xs">
        <x-icon name="plus" class="h-4 w-4" /> Nouvelle prestation
    </a>
@endsection

@section('content')

<form method="GET" class="card mb-6 p-4">
    <div class="relative max-w-md">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher une prestation…" class="field pl-10">
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-espresso-700/40" />
    </div>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 bg-cream-50 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="px-5 py-3">Prestation</th>
                    <th class="px-5 py-3">Catégorie</th>
                    <th class="px-5 py-3 text-right">Tarif</th>
                    <th class="px-5 py-3 text-right">Durée</th>
                    <th class="px-5 py-3">État</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @forelse ($services as $service)
                    <tr class="transition hover:bg-cream-50">
                        <td class="px-5 py-4">
                            <span class="block font-medium text-espresso-900">{{ $service->name }}</span>
                            <span class="block max-w-md truncate text-xs text-espresso-700/55">{{ $service->description }}</span>
                        </td>
                        <td class="px-5 py-4 text-espresso-700/75">{{ $service->category?->name }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right font-medium text-gold-600">{{ $service->price_label }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-espresso-700/75">{{ $service->duration_label }}</td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] ring-1 {{ $service->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                                    {{ $service->is_active ? 'Active' : 'Masquée' }}
                                </span>
                                @if ($service->is_featured)
                                    <span class="rounded-full bg-gold-500/15 px-2.5 py-0.5 text-[11px] text-gold-600 ring-1 ring-gold-300">Mise en avant</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $service) }}"
                                   class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
                                    <x-icon name="edit" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                      onsubmit="return confirm('Supprimer cette prestation ?')">
                                    @csrf @method('DELETE')
                                    <button class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 text-rose-500 transition hover:border-rose-300">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-16 text-center text-espresso-700/50">Aucune prestation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $services->links() }}</div>

@endsection
