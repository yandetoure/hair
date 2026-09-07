@extends('layouts.admin')

@section('title', $appointment->exists ? 'Modifier un rendez-vous' : 'Nouveau rendez-vous')
@section('heading', $appointment->exists ? 'Modifier le rendez-vous' : 'Nouveau rendez-vous')
@section('subheading', $appointment->exists ? $appointment->reference : 'Prise de rendez-vous au comptoir ou par téléphone')

@section('content')

<form method="POST" action="{{ $appointment->exists ? route('admin.appointments.update', $appointment) : route('admin.appointments.store') }}"
      class="grid max-w-4xl gap-6">
    @csrf
    @if ($appointment->exists) @method('PUT') @endif

    <div class="card p-6">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Cliente</h2>
        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label" for="customer_name">Nom & prénom *</label>
                <input type="text" name="customer_name" id="customer_name" class="field" required
                       value="{{ old('customer_name', $appointment->customer_name) }}">
            </div>
            <div>
                <label class="label" for="customer_phone">Téléphone *</label>
                <input type="tel" name="customer_phone" id="customer_phone" class="field" required
                       value="{{ old('customer_phone', $appointment->customer_phone) }}">
            </div>
            <div class="sm:col-span-2">
                <label class="label" for="customer_email">E-mail</label>
                <input type="email" name="customer_email" id="customer_email" class="field"
                       value="{{ old('customer_email', $appointment->customer_email) }}">
            </div>
        </div>
    </div>

    <div class="card p-6">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Prestation & créneau</h2>
        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label" for="service_id">Prestation *</label>
                <select name="service_id" id="service_id" class="field" required>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id', $appointment->service_id) == $service->id)>
                            {{ $service->name }} — {{ $service->price_label }} ({{ $service->duration_label }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="staff_member_id">Praticienne</label>
                <select name="staff_member_id" id="staff_member_id" class="field">
                    <option value="">Non assignée</option>
                    @foreach ($staff as $member)
                        <option value="{{ $member->id }}" @selected(old('staff_member_id', $appointment->staff_member_id) == $member->id)>{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="date">Date *</label>
                <input type="date" name="date" id="date" class="field" required
                       value="{{ old('date', optional($appointment->starts_at)->toDateString() ?? now()->toDateString()) }}">
            </div>
            <div>
                <label class="label" for="time">Heure *</label>
                <input type="time" name="time" id="time" class="field" required step="900"
                       value="{{ old('time', optional($appointment->starts_at)->format('H:i') ?? '09:00') }}">
            </div>
            <div>
                <label class="label" for="price">Montant ({{ config('salon.currency') }})</label>
                <input type="number" name="price" id="price" class="field" min="0"
                       value="{{ old('price', $appointment->price) }}" placeholder="Tarif de la prestation">
            </div>
            <div>
                <label class="label" for="status">Statut *</label>
                <select name="status" id="status" class="field" required>
                    @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $appointment->status ?? 'confirmed') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card p-6">
        <h2 class="font-display text-xl font-semibold text-espresso-900">Notes</h2>
        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label" for="notes">Demande de la cliente</label>
                <textarea name="notes" id="notes" rows="3" class="field">{{ old('notes', $appointment->notes) }}</textarea>
            </div>
            <div>
                <label class="label" for="admin_notes">Note interne</label>
                <textarea name="admin_notes" id="admin_notes" rows="3" class="field">{{ old('admin_notes', $appointment->admin_notes) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="btn-gold">{{ $appointment->exists ? 'Enregistrer les modifications' : 'Créer le rendez-vous' }}</button>
        <a href="{{ route('admin.appointments.index') }}" class="btn-outline">Annuler</a>
    </div>
</form>

@endsection
