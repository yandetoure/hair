@extends('layouts.public')

@section('title', 'Prendre rendez-vous — '.config('salon.name'))
@section('description', 'Réservez votre créneau en ligne : coiffure, tresses, soins, manucure, pédicure. Confirmation rapide par téléphone.')

@section('content')

@php
    $servicesJson = $categories->flatMap->activeServices->map(fn ($s) => [
        'id' => $s->id,
        'name' => $s->name,
        'price' => $s->price,
        'price_label' => $s->price_label,
        'duration' => $s->duration,
        'duration_label' => $s->duration_label,
        'category' => $s->category?->name,
    ])->values();
@endphp

<section class="relative overflow-hidden border-b border-espresso-900/10">
    <div class="texture-gradient absolute inset-0 opacity-60"></div>
    <div class="relative mx-auto max-w-3xl px-5 py-16 text-center lg:px-8">
        <span class="eyebrow">Réservation</span>
        <h1 class="mt-4 font-display text-5xl font-semibold text-espresso-900">Prendre rendez-vous</h1>
        <p class="mt-4 text-espresso-700/85">
            Trois étapes, une minute. Vous recevez une confirmation par téléphone ou WhatsApp.
        </p>
    </div>
</section>

<form method="POST" action="{{ route('booking.store') }}" id="booking-form"
      class="mx-auto grid max-w-7xl gap-10 px-5 py-16 lg:grid-cols-[1fr_380px] lg:px-8">
    @csrf
    <input type="hidden" name="service_id" id="service_id" value="{{ old('service_id', $selectedService?->id) }}">
    <input type="hidden" name="date" id="date" value="{{ old('date') }}">
    <input type="hidden" name="time" id="time" value="{{ old('time') }}">

    <div class="space-y-12">
        {{-- ÉTAPE 1 --}}
        <section>
            <div class="flex items-center gap-4">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-espresso-900 font-display text-lg font-semibold text-gold-300">1</span>
                <h2 class="font-display text-3xl font-semibold text-espresso-900">Votre prestation</h2>
            </div>

            <div class="mt-8 space-y-8">
                @foreach ($categories as $category)
                    @continue($category->activeServices->isEmpty())
                    <div>
                        <h3 class="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-gold-600">
                            <x-icon :name="$category->icon" class="h-4 w-4" /> {{ $category->name }}
                        </h3>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($category->activeServices as $service)
                                <label class="service-option group flex cursor-pointer items-start gap-3 rounded-xl border border-espresso-900/12 bg-white p-4 transition hover:border-gold-500">
                                    <input type="radio" name="service_choice" value="{{ $service->id }}"
                                           class="mt-1 accent-[#c19a3c]"
                                           @checked(old('service_id', $selectedService?->id) == $service->id)>
                                    <span class="flex-1">
                                        <span class="block font-medium text-espresso-900">{{ $service->name }}</span>
                                        <span class="mt-0.5 flex items-center gap-3 text-xs text-espresso-700/65">
                                            <span class="text-gold-600">{{ $service->price_label }}</span>
                                            <span class="inline-flex items-center gap-1"><x-icon name="clock" class="h-3 w-3" />{{ $service->duration_label }}</span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($staff->isNotEmpty())
                <div class="mt-8">
                    <label class="label" for="staff_member_id">Votre praticienne (facultatif)</label>
                    <select name="staff_member_id" id="staff_member_id" class="field max-w-sm">
                        <option value="">Peu importe / la première disponible</option>
                        @foreach ($staff as $member)
                            <option value="{{ $member->id }}" @selected(old('staff_member_id') == $member->id)>
                                {{ $member->name }} — {{ $member->role }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </section>

        {{-- ÉTAPE 2 --}}
        <section>
            <div class="flex items-center gap-4">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-espresso-900 font-display text-lg font-semibold text-gold-300">2</span>
                <h2 class="font-display text-3xl font-semibold text-espresso-900">Le jour & l'heure</h2>
            </div>

            <div class="mt-8 flex gap-3 overflow-x-auto pb-2" id="days">
                @foreach ($days as $day)
                    <button type="button" data-day="{{ $day->toDateString() }}"
                            class="day-option min-w-[92px] rounded-xl border border-espresso-900/12 bg-white px-4 py-3 text-center transition hover:border-gold-500">
                        <span class="block text-[10px] uppercase tracking-[0.16em] text-espresso-700/55">{{ $day->locale('fr')->isoFormat('ddd') }}</span>
                        <span class="mt-1 block font-display text-2xl font-semibold text-espresso-900">{{ $day->format('d') }}</span>
                        <span class="block text-[10px] uppercase tracking-[0.14em] text-espresso-700/55">{{ $day->locale('fr')->isoFormat('MMM') }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-6" id="slots-container">
                <p class="rounded-xl border border-dashed border-espresso-900/15 px-5 py-6 text-center text-sm text-espresso-700/60">
                    Choisissez d'abord une prestation, puis un jour.
                </p>
            </div>
        </section>

        {{-- ÉTAPE 3 --}}
        <section>
            <div class="flex items-center gap-4">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-espresso-900 font-display text-lg font-semibold text-gold-300">3</span>
                <h2 class="font-display text-3xl font-semibold text-espresso-900">Vos coordonnées</h2>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label" for="customer_name">Nom & prénom *</label>
                    <input type="text" name="customer_name" id="customer_name" class="field" required
                           value="{{ old('customer_name') }}" placeholder="Aïcha Traoré">
                </div>
                <div>
                    <label class="label" for="customer_phone">Téléphone *</label>
                    <input type="tel" name="customer_phone" id="customer_phone" class="field" required
                           value="{{ old('customer_phone') }}" placeholder="+225 07 00 00 00 00">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="customer_email">E-mail (facultatif)</label>
                    <input type="email" name="customer_email" id="customer_email" class="field"
                           value="{{ old('customer_email') }}" placeholder="vous@exemple.com">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="notes">Précisions (facultatif)</label>
                    <textarea name="notes" id="notes" rows="3" class="field"
                              placeholder="Longueur souhaitée, mèches fournies, allergie…">{{ old('notes') }}</textarea>
                </div>
            </div>
        </section>
    </div>

    {{-- RÉCAPITULATIF --}}
    <aside class="lg:sticky lg:top-28 lg:self-start">
        <div class="card overflow-hidden">
            <div class="bg-espresso-900 p-6 text-cream-100">
                <span class="text-xs uppercase tracking-[0.22em] text-gold-300">Récapitulatif</span>
                <p class="mt-2 font-display text-2xl font-semibold">Votre rendez-vous</p>
            </div>

            <dl class="divide-y divide-espresso-900/8 p-6 text-sm">
                <div class="flex justify-between gap-4 pb-4">
                    <dt class="text-espresso-700/60">Prestation</dt>
                    <dd class="text-right font-medium text-espresso-900" id="summary-service">—</dd>
                </div>
                <div class="flex justify-between gap-4 py-4">
                    <dt class="text-espresso-700/60">Durée</dt>
                    <dd class="text-right font-medium text-espresso-900" id="summary-duration">—</dd>
                </div>
                <div class="flex justify-between gap-4 py-4">
                    <dt class="text-espresso-700/60">Date</dt>
                    <dd class="text-right font-medium text-espresso-900" id="summary-date">—</dd>
                </div>
                <div class="flex justify-between gap-4 py-4">
                    <dt class="text-espresso-700/60">Heure</dt>
                    <dd class="text-right font-medium text-espresso-900" id="summary-time">—</dd>
                </div>
                <div class="flex items-center justify-between gap-4 pt-4">
                    <dt class="font-display text-lg text-espresso-900">Tarif</dt>
                    <dd class="font-display text-2xl font-semibold text-gold-600" id="summary-price">—</dd>
                </div>
            </dl>

            <div class="border-t border-espresso-900/8 p-6">
                <button type="submit" class="btn-gold w-full py-4">Confirmer ma réservation</button>
                <p class="mt-3 text-center text-xs leading-relaxed text-espresso-700/55">
                    Aucun paiement en ligne. Le règlement se fait au salon.
                </p>
            </div>
        </div>

        <div class="card mt-6 p-6 text-sm text-espresso-700/75">
            <p class="flex items-center gap-2 font-medium text-espresso-900">
                <x-icon name="phone" class="h-4 w-4 text-gold-600" /> Besoin d'aide ?
            </p>
            <p class="mt-2 leading-relaxed">
                Appelez-nous au <a href="tel:{{ preg_replace('/\s+/', '', config('salon.phone')) }}" class="text-gold-600">{{ config('salon.phone') }}</a>
                pour un créneau de dernière minute.
            </p>
        </div>
    </aside>
</form>

@push('scripts')
<script>
    const services = @json($servicesJson);
    const slotsUrl = @json(route('booking.slots'));

    const state = {
        service: services.find((s) => s.id == {{ (int) old('service_id', $selectedService?->id ?? 0) }}) || null,
        date: @json(old('date')),
        time: @json(old('time')),
    };

    const el = (id) => document.getElementById(id);

    function markSelected(nodes, predicate) {
        nodes.forEach((node) => {
            const on = predicate(node);
            node.classList.toggle('border-gold-500', on);
            node.classList.toggle('bg-gold-500/10', on);
            node.classList.toggle('ring-1', on);
            node.classList.toggle('ring-gold-500/40', on);
        });
    }

    function refreshSummary() {
        el('summary-service').textContent = state.service ? state.service.name : '—';
        el('summary-duration').textContent = state.service ? state.service.duration_label : '—';
        el('summary-price').textContent = state.service ? state.service.price_label : '—';
        el('summary-date').textContent = state.date
            ? new Date(state.date + 'T00:00:00').toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' })
            : '—';
        el('summary-time').textContent = state.time || '—';

        el('service_id').value = state.service ? state.service.id : '';
        el('date').value = state.date || '';
        el('time').value = state.time || '';
    }

    async function loadSlots() {
        const container = el('slots-container');

        if (!state.service || !state.date) {
            container.innerHTML = '<p class="rounded-xl border border-dashed border-espresso-900/15 px-5 py-6 text-center text-sm text-espresso-700/60">Choisissez d\'abord une prestation, puis un jour.</p>';
            return;
        }

        container.innerHTML = '<p class="rounded-xl border border-dashed border-espresso-900/15 px-5 py-6 text-center text-sm text-espresso-700/60">Recherche des créneaux…</p>';

        const params = new URLSearchParams({ service_id: state.service.id, date: state.date });
        const response = await fetch(`${slotsUrl}?${params}`, { headers: { Accept: 'application/json' } });
        const data = await response.json();

        if (!data.slots.length) {
            container.innerHTML = '<p class="rounded-xl border border-dashed border-rose-200 bg-rose-50/60 px-5 py-6 text-center text-sm text-rose-700">Le salon est fermé ce jour-là. Choisissez une autre date.</p>';
            return;
        }

        const available = data.slots.filter((slot) => slot.available);

        container.innerHTML = `
            <p class="mb-4 text-sm text-espresso-700/70">
                <span class="font-medium text-espresso-900">${available.length}</span> créneaux disponibles le ${data.label}
                <span class="text-espresso-700/50">— durée ${data.duration}</span>
            </p>
            <div class="flex flex-wrap gap-2">
                ${data.slots.map((slot) => `
                    <button type="button" data-slot="${slot.time}" ${slot.available ? '' : 'disabled'}
                        class="slot-option rounded-lg border px-4 py-2.5 text-sm transition ${
                            slot.available
                                ? 'border-espresso-900/12 bg-white text-espresso-900 hover:border-gold-500'
                                : 'cursor-not-allowed border-espresso-900/5 bg-cream-100 text-espresso-700/30 line-through'
                        }">${slot.time}</button>
                `).join('')}
            </div>`;

        if (state.time && !available.some((slot) => slot.time === state.time)) {
            state.time = null;
        }

        markSelected([...container.querySelectorAll('.slot-option')], (node) => node.dataset.slot === state.time);
        refreshSummary();
    }

    document.querySelectorAll('input[name="service_choice"]').forEach((input) => {
        input.addEventListener('change', () => {
            state.service = services.find((s) => s.id == input.value) || null;
            state.time = null;
            markSelected([...document.querySelectorAll('.service-option')], (node) => node.contains(input) && input.checked);
            refreshSummary();
            loadSlots();
        });
    });

    document.querySelectorAll('.day-option').forEach((button) => {
        button.addEventListener('click', () => {
            state.date = button.dataset.day;
            state.time = null;
            markSelected([...document.querySelectorAll('.day-option')], (node) => node.dataset.day === state.date);
            refreshSummary();
            loadSlots();
        });
    });

    document.addEventListener('click', (event) => {
        const slot = event.target.closest('.slot-option');

        if (slot && !slot.disabled) {
            state.time = slot.dataset.slot;
            markSelected([...document.querySelectorAll('.slot-option')], (node) => node.dataset.slot === state.time);
            refreshSummary();
        }
    });

    document.getElementById('booking-form').addEventListener('submit', (event) => {
        if (!state.service || !state.date || !state.time) {
            event.preventDefault();
            alert('Merci de choisir une prestation, une date et un créneau horaire.');
        }
    });

    // Pré-sélection (retour d'erreur ou lien « réserver cette prestation »)
    markSelected([...document.querySelectorAll('.service-option')], (node) => node.querySelector('input').checked);
    markSelected([...document.querySelectorAll('.day-option')], (node) => node.dataset.day === state.date);
    refreshSummary();
    if (state.service && state.date) {
        loadSlots();
    }
</script>
@endpush

@endsection
