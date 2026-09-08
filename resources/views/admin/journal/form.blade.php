@extends('layouts.admin')

@section('title', $post->exists ? 'Modifier un article' : 'Nouvel article')
@section('heading', $post->exists ? "Modifier l'article" : 'Nouvel article')
@section('subheading', $post->exists ? $post->title : 'Partagez vos conseils avec vos clientes')

@section('content')

<form method="POST" enctype="multipart/form-data"
      action="{{ $post->exists ? route('admin.journal.update', $post) : route('admin.journal.store') }}"
      class="grid max-w-4xl gap-6">
    @csrf
    @if ($post->exists) @method('PUT') @endif

    <div class="card p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="title">Titre *</label>
                <input type="text" name="title" id="title" class="field" required value="{{ old('title', $post->title) }}">
            </div>

            <div>
                <label class="label" for="category">Rubrique *</label>
                <input type="text" name="category" id="category" class="field" required list="post-categories"
                       value="{{ old('category', $post->category) }}">
                <datalist id="post-categories">
                    @foreach (['Conseils', 'Soins', 'Tresses', 'Onglerie', 'Beauté'] as $category)
                        <option value="{{ $category }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="label" for="reading_time">Temps de lecture (min)</label>
                <input type="number" name="reading_time" id="reading_time" class="field" min="1" max="60"
                       value="{{ old('reading_time', $post->reading_time ?? 3) }}">
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="excerpt">Chapô / résumé</label>
                <textarea name="excerpt" id="excerpt" rows="2" class="field">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="body">Contenu *</label>
                <textarea name="body" id="body" rows="16" class="field font-mono text-xs leading-relaxed" required>{{ old('body', $post->body) }}</textarea>
                <p class="mt-2 text-xs text-espresso-700/55">
                    Séparez les paragraphes par une ligne vide. Commencez une ligne par <code class="rounded bg-cream-200 px-1">##</code>
                    pour un sous-titre, ou par <code class="rounded bg-cream-200 px-1">-</code> pour une liste à puces.
                </p>
            </div>

            <div>
                <label class="label" for="image">Image de couverture</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full rounded-xl border border-espresso-900/15 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-espresso-900 file:px-3 file:py-1.5 file:text-xs file:text-cream-100">
                @if ($post->image)
                    <img src="{{ salon_image($post->image) }}" alt="" class="mt-3 h-24 w-40 rounded-lg object-cover">
                @endif
            </div>

            <div>
                <label class="label" for="published_at">Date de publication</label>
                <input type="date" name="published_at" id="published_at" class="field"
                       value="{{ old('published_at', optional($post->published_at)->toDateString()) }}">
            </div>

            <label class="flex items-center gap-2 text-sm sm:col-span-2">
                <input type="checkbox" name="is_published" value="1" class="rounded accent-[#c19a3c]"
                       @checked(old('is_published', $post->is_published ?? true))> Publier sur le site
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $post->exists ? 'Enregistrer' : "Publier l'article" }}</button>
        <a href="{{ route('admin.journal.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
