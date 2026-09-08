@extends('layouts.admin')

@section('title', 'Journal')
@section('heading', 'Journal beauté')
@section('subheading', $posts->total().' articles')

@section('actions')
    <a href="{{ route('admin.journal.create') }}" class="btn-gold py-2.5 text-xs">
        <x-icon name="plus" class="h-4 w-4" /> Nouvel article
    </a>
@endsection

@section('content')

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 bg-cream-50 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="px-5 py-3">Article</th>
                    <th class="px-5 py-3">Rubrique</th>
                    <th class="px-5 py-3">Publication</th>
                    <th class="px-5 py-3">État</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @forelse ($posts as $post)
                    <tr class="transition hover:bg-cream-50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if ($post->image)
                                    <img src="{{ salon_image($post->image) }}" alt="" class="h-12 w-16 rounded-lg object-cover">
                                @else
                                    <span class="grid h-12 w-16 place-items-center rounded-lg bg-cream-200 text-espresso-700/40">
                                        <x-icon name="edit" class="h-4 w-4" />
                                    </span>
                                @endif
                                <div>
                                    <span class="block font-medium text-espresso-900">{{ $post->title }}</span>
                                    <span class="block max-w-md truncate text-xs text-espresso-700/55">{{ $post->excerpt }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-espresso-700/75">{{ $post->category }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-espresso-700/75">
                            {{ $post->published_at?->locale('fr')->isoFormat('D MMM YYYY') ?? '—' }}
                            <span class="block text-xs text-espresso-700/45">{{ $post->reading_time }} min de lecture</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-0.5 text-[11px] ring-1 {{ $post->is_published ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                                {{ $post->is_published ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('journal.show', $post) }}" target="_blank"
                                   class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
                                    <x-icon name="arrow-right" class="h-4 w-4" />
                                </a>
                                <a href="{{ route('admin.journal.edit', $post) }}"
                                   class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
                                    <x-icon name="edit" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.journal.destroy', $post) }}"
                                      onsubmit="return confirm('Supprimer cet article ?')">
                                    @csrf @method('DELETE')
                                    <button class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 text-rose-500 transition hover:border-rose-300">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-16 text-center text-espresso-700/50">Aucun article.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $posts->links() }}</div>

@endsection
