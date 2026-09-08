@extends('layouts.admin')

@section('title', $offer->exists ? 'Modifier un forfait' : 'Nouveau forfait')
@section('heading', $offer->exists ? 'Modifier le forfait' : 'Nouveau forfait')
@section('subheading', $offer->exists ? $offer->name : 'Regroupez plusieurs prestations à tarif préférentiel')

@section('content')

<form method="POST" enctype="multipart/form-data"
      action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}"
      class="grid max-w-4xl gap-6">
    @csrf
    @if ($offer->exists) @method('PUT') @endif

    <div class="card p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label" for="name">Nom du forfait *</label>
                <input type="text" name="name" id="name" class="field" required value="{{ old('name', $offer->name) }}"
                       placeholder="Rituel Mariée">
            </div>
            <div>
                <label class="label" for="tagline">Accroche</label>
                <input type="text" name="tagline" id="tagline" class="field" value="{{ old('tagline', $offer->tagline) }}"
                       placeholder="Le grand jour, de la tête aux ongles">
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="field">{{ old('description', $offer->description) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="includes">Prestations incluses — une par ligne</label>
                <textarea name="includes" id="includes" rows="6" class="field"
                          placeholder="Essai coiffure + coiffure du jour J&#10;Mise en beauté mariée&#10;Manucure">{{ old('includes', $offer->includes) }}</textarea>
            </div>

            <div>
                <label class="label" for="price">Tarif du forfait ({{ config('salon.currency') }}) *</label>
                <input type="number" name="price" id="price" class="field" required min="0" value="{{ old('price', $offer->price) }}">
            </div>
            <div>
                <label class="label" for="compare_price">Tarif si pris séparément</label>
                <input type="number" name="compare_price" id="compare_price" class="field" min="0"
                       value="{{ old('compare_price', $offer->compare_price) }}" placeholder="Affiche l'économie réalisée">
            </div>

            <div>
                <label class="label" for="duration">Durée totale (minutes)</label>
                <input type="number" name="duration" id="duration" class="field" min="0" max="900" step="15"
                       value="{{ old('duration', $offer->duration) }}">
            </div>
            <div>
                <label class="label" for="position">Ordre d'affichage</label>
                <input type="number" name="position" id="position" class="field" min="0" value="{{ old('position', $offer->position ?? 0) }}">
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="image">Photo</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full rounded-xl border border-espresso-900/15 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-espresso-900 file:px-3 file:py-1.5 file:text-xs file:text-cream-100">
                @if ($offer->image)
                    <img src="{{ salon_image($offer->image) }}" alt="" class="mt-3 h-28 w-40 rounded-lg object-cover">
                @endif
            </div>

            <div class="flex flex-wrap gap-6 sm:col-span-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" class="rounded accent-[#c19a3c]"
                           @checked(old('is_active', $offer->is_active ?? true))> Visible sur le site
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" class="rounded accent-[#c19a3c]"
                           @checked(old('is_featured', $offer->is_featured))> Mettre en avant
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $offer->exists ? 'Enregistrer' : 'Créer le forfait' }}</button>
        <a href="{{ route('admin.offers.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
