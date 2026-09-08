@extends('layouts.admin')

@section('title', $member->exists ? 'Modifier un membre' : 'Nouveau membre')
@section('heading', $member->exists ? 'Modifier le membre' : 'Ajouter un membre')
@section('subheading', $member->exists ? $member->name : "Présentez votre équipe sur le site")

@section('content')

<form method="POST" enctype="multipart/form-data"
      action="{{ $member->exists ? route('admin.staff.update', $member) : route('admin.staff.store') }}"
      class="grid max-w-3xl gap-6">
    @csrf
    @if ($member->exists) @method('PUT') @endif

    <div class="card p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label" for="name">Nom *</label>
                <input type="text" name="name" id="name" class="field" required value="{{ old('name', $member->name) }}">
            </div>
            <div>
                <label class="label" for="role">Spécialité</label>
                <input type="text" name="role" id="role" class="field" value="{{ old('role', $member->role) }}"
                       placeholder="Coiffeuse & experte tresses">
            </div>
            <div class="sm:col-span-2">
                <label class="label" for="bio">Présentation</label>
                <textarea name="bio" id="bio" rows="3" class="field">{{ old('bio', $member->bio) }}</textarea>
            </div>
            <div>
                <label class="label" for="photo">Photo</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                       class="w-full rounded-xl border border-espresso-900/15 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-espresso-900 file:px-3 file:py-1.5 file:text-xs file:text-cream-100">
                @if ($member->photo)
                    <img src="{{ salon_image($member->photo) }}" alt="" class="mt-3 h-24 w-24 rounded-lg object-cover">
                @endif
            </div>
            <div>
                <label class="label" for="position">Ordre d'affichage</label>
                <input type="number" name="position" id="position" class="field" min="0" value="{{ old('position', $member->position ?? 0) }}">
            </div>
            <label class="flex items-center gap-2 text-sm sm:col-span-2">
                <input type="checkbox" name="is_active" value="1" class="rounded accent-[#c19a3c]"
                       @checked(old('is_active', $member->is_active ?? true))> Visible sur le site et dans la réservation
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $member->exists ? 'Enregistrer' : 'Ajouter' }}</button>
        <a href="{{ route('admin.staff.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
