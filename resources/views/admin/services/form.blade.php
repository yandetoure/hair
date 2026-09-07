@extends('layouts.admin')

@section('title', $service->exists ? 'Modifier une prestation' : 'Nouvelle prestation')
@section('heading', $service->exists ? 'Modifier la prestation' : 'Nouvelle prestation')
@section('subheading', $service->exists ? $service->name : 'Ajoutez une prestation à la carte du salon')

@section('content')

<form method="POST" enctype="multipart/form-data"
      action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
      class="grid max-w-4xl gap-6">
    @csrf
    @if ($service->exists) @method('PUT') @endif

    <div class="card p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="name">Nom de la prestation *</label>
                <input type="text" name="name" id="name" class="field" required value="{{ old('name', $service->name) }}">
            </div>

            <div>
                <label class="label" for="service_category_id">Catégorie *</label>
                <select name="service_category_id" id="service_category_id" class="field" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('service_category_id', $service->service_category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label" for="duration">Durée (minutes) *</label>
                <input type="number" name="duration" id="duration" class="field" required min="10" max="600" step="5"
                       value="{{ old('duration', $service->duration) }}">
            </div>

            <div>
                <label class="label" for="price">Tarif ({{ config('salon.currency') }}) *</label>
                <input type="number" name="price" id="price" class="field" required min="0" value="{{ old('price', $service->price) }}">
            </div>

            <div>
                <label class="label" for="price_max">Tarif maximum (facultatif)</label>
                <input type="number" name="price_max" id="price_max" class="field" min="0" value="{{ old('price_max', $service->price_max) }}"
                       placeholder="Pour afficher une fourchette de prix">
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="field">{{ old('description', $service->description) }}</textarea>
            </div>

            <div>
                <label class="label" for="position">Ordre d'affichage</label>
                <input type="number" name="position" id="position" class="field" min="0" value="{{ old('position', $service->position ?? 0) }}">
            </div>

            <div>
                <label class="label" for="image">Photo</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full rounded-xl border border-espresso-900/15 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-espresso-900 file:px-3 file:py-1.5 file:text-xs file:text-cream-100">
                @if ($service->image)
                    <img src="{{ Storage::url($service->image) }}" alt="" class="mt-3 h-24 w-32 rounded-lg object-cover">
                @endif
            </div>

            <div class="flex flex-wrap gap-6 sm:col-span-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" class="rounded accent-[#c19a3c]"
                           @checked(old('is_active', $service->is_active ?? true))> Visible sur le site
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" class="rounded accent-[#c19a3c]"
                           @checked(old('is_featured', $service->is_featured))> Mettre en avant sur l'accueil
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $service->exists ? 'Enregistrer' : 'Créer la prestation' }}</button>
        <a href="{{ route('admin.services.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
