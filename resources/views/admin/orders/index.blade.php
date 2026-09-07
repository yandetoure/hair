@extends('layouts.admin')

@section('title', 'Commandes')
@section('heading', 'Commandes boutique')
@section('subheading', $orders->total().' commandes enregistrées')

@section('content')

<form method="GET" class="card mb-6 grid gap-3 p-4 sm:grid-cols-3">
    <div class="relative sm:col-span-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cliente, téléphone ou référence…" class="field pl-10">
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-espresso-700/40" />
    </div>
    <select name="status" class="field" onchange="this.form.submit()">
        <option value="">Tous les statuts</option>
        @foreach (\App\Models\Order::STATUSES as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 bg-cream-50 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="px-5 py-3">Référence</th>
                    <th class="px-5 py-3">Cliente</th>
                    <th class="px-5 py-3">Articles</th>
                    <th class="px-5 py-3">Réception</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @forelse ($orders as $order)
                    <tr class="transition hover:bg-cream-50">
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-espresso-900 hover:text-gold-600">{{ $order->reference }}</a>
                            <span class="block text-xs text-espresso-700/55">{{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY · HH:mm') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="block font-medium text-espresso-900">{{ $order->customer_name }}</span>
                            <span class="block text-xs text-espresso-700/55">{{ $order->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-4 text-espresso-700/75">{{ $order->items->sum('quantity') }}</td>
                        <td class="px-5 py-4 text-espresso-700/75">{{ $order->delivery_mode === 'delivery' ? 'Livraison' : 'Retrait' }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right font-medium text-gold-600">{{ fcfa($order->total) }}</td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="rounded-lg border border-espresso-900/12 px-2.5 py-1.5 text-xs">
                                    @foreach (\App\Models\Order::STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="inline-flex items-center gap-1 text-xs text-gold-600 hover:underline">
                                Détail <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-espresso-700/50">Aucune commande pour ces critères.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>

@endsection
