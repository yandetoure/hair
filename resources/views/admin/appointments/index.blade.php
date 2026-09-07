@extends('layouts.admin')

@section('title', 'Rendez-vous')
@section('heading', 'Rendez-vous')
@section('subheading', $appointments->total().' rendez-vous enregistrés')

@section('content')

<form method="GET" class="card mb-6 grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-5">
    <div class="relative lg:col-span-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, téléphone ou référence…"
               class="field pl-10">
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-espresso-700/40" />
    </div>

    <select name="status" class="field">
        <option value="">Tous les statuts</option>
        @foreach (\App\Models\Appointment::STATUSES as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>

    <input type="date" name="from" value="{{ request('from') }}" class="field" title="Du">
    <input type="date" name="to" value="{{ request('to') }}" class="field" title="Au">

    <div class="flex gap-2 sm:col-span-2 lg:col-span-5">
        <button class="btn-dark py-2.5 text-xs">Filtrer</button>
        <a href="{{ route('admin.appointments.index') }}" class="btn-outline py-2.5 text-xs">Réinitialiser</a>
    </div>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 bg-cream-50 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="px-5 py-3">Date & heure</th>
                    <th class="px-5 py-3">Cliente</th>
                    <th class="px-5 py-3">Prestation</th>
                    <th class="px-5 py-3">Praticienne</th>
                    <th class="px-5 py-3 text-right">Montant</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @forelse ($appointments as $appointment)
                    <tr class="transition hover:bg-cream-50">
                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="block font-medium text-espresso-900">{{ $appointment->starts_at->locale('fr')->isoFormat('ddd D MMM YYYY') }}</span>
                            <span class="block text-xs text-espresso-700/55">{{ $appointment->starts_at->format('H:i') }} → {{ $appointment->ends_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="block font-medium text-espresso-900">{{ $appointment->customer_name }}</span>
                            <span class="block text-xs text-espresso-700/55">{{ $appointment->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="block">{{ $appointment->service?->name }}</span>
                            <span class="block text-xs text-espresso-700/45">{{ $appointment->reference }}</span>
                        </td>
                        <td class="px-5 py-4 text-espresso-700/75">{{ $appointment->staff?->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right font-medium text-gold-600">{{ fcfa($appointment->price) }}</td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="rounded-lg border border-espresso-900/12 px-2.5 py-1.5 text-xs">
                                    @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($appointment->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                   class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 transition hover:border-gold-500 hover:text-gold-600">
                                    <x-icon name="edit" class="h-4 w-4" />
                                </a>
                                <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}"
                                      onsubmit="return confirm('Supprimer définitivement ce rendez-vous ?')">
                                    @csrf @method('DELETE')
                                    <button class="grid h-8 w-8 place-items-center rounded-lg border border-espresso-900/12 text-rose-500 transition hover:border-rose-300">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-espresso-700/50">Aucun rendez-vous ne correspond à ces critères.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $appointments->links() }}
</div>

@endsection
