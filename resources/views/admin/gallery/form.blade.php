@extends('layouts.admin')

@section('title', $item->exists ? 'Modifier une réalisation' : 'Nouvelle réalisation')
@section('heading', $item->exists ? 'Modifier la réalisation' : 'Nouvelle réalisation')
@section('subheading', $item->exists ? $item->title : 'Alimentez la galerie « Nos réalisations » du site')

@section('content')

<form method="POST" enctype="multipart/form-data"
      action="{{ $item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}"
      class="grid max-w-3xl gap-6">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="card p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="title">Titre *</label>
                <input type="text" name="title" id="title" class="field" required
                       value="{{ old('title', $item->title) }}" placeholder="Box braids longueur taille">
            </div>

            <div>
                <label class="label" for="category">Univers *</label>
                <input type="text" name="category" id="category" class="field" required list="gallery-categories"
                       value="{{ old('category', $item->category) }}" placeholder="Tresses">
                <datalist id="gallery-categories">
                    @foreach (\App\Models\GalleryItem::distinct()->pluck('category') as $category)
                        <option value="{{ $category }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="label" for="format">Format d'affichage *</label>
                <select name="format" id="format" class="field" required>
                    <option value="portrait" @selected(old('format', $item->format) === 'portrait')>Portrait (vertical)</option>
                    <option value="paysage" @selected(old('format', $item->format) === 'paysage')>Paysage (horizontal)</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="description">Description</label>
                <textarea name="description" id="description" rows="2" class="field"
                          placeholder="Visible au survol de la photo sur le site">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label class="label" for="image">Photo {{ $item->exists ? '' : '*' }}</label>
                <input type="file" name="image" id="image" accept="image/*" @required(! $item->exists)
                       class="w-full rounded-xl border border-espresso-900/15 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-espresso-900 file:px-3 file:py-1.5 file:text-xs file:text-cream-100">
                @if ($item->image)
                    <img src="{{ salon_image($item->image) }}" alt="" class="mt-3 h-28 w-36 rounded-lg object-cover">
                @endif
            </div>

            <div>
                <label class="label" for="position">Ordre d'affichage</label>
                <input type="number" name="position" id="position" class="field" min="0"
                       value="{{ old('position', $item->position ?? 0) }}">
            </div>

            <label class="flex items-center gap-2 text-sm sm:col-span-2">
                <input type="checkbox" name="is_published" value="1" class="rounded accent-[#c19a3c]"
                       @checked(old('is_published', $item->is_published ?? true))> Publiée sur le site
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $item->exists ? 'Enregistrer' : 'Ajouter à la galerie' }}</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
