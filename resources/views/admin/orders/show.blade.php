@extends('layouts.admin')

@section('title', 'Commande '.$order->reference)
@section('heading', 'Commande '.$order->reference)
@section('subheading', 'Reçue le '.$order->created_at->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm'))

@section('actions')
    <a href="{{ route('admin.orders.index') }}" class="btn-outline py-2.5 text-xs">
        <x-icon name="chevron-left" class="h-4 w-4" /> Toutes les commandes
    </a>
@endsection

@section('content')

<div class="grid max-w-5xl gap-6 lg:grid-cols-[1fr_320px]">
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-espresso-900/8 bg-cream-50 text-left text-xs uppercase tracking-[0.12em] text-espresso-700/55">
                <tr>
                    <th class="px-5 py-3">Produit</th>
                    <th class="px-5 py-3 text-right">P.U.</th>
                    <th class="px-5 py-3 text-right">Qté</th>
                    <th class="px-5 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-espresso-900/6">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-5 py-4 font-medium text-espresso-900">{{ $item->name }}</td>
                        <td class="px-5 py-4 text-right text-espresso-700/75">{{ fcfa($item->unit_price) }}</td>
                        <td class="px-5 py-4 text-right text-espresso-700/75">{{ $item->quantity }}</td>
                        <td class="px-5 py-4 text-right font-medium">{{ fcfa($item->line_total) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-espresso-900/8 bg-cream-50 text-sm">
                <tr>
                    <td colspan="3" class="px-5 py-2.5 text-right text-espresso-700/65">Sous-total</td>
                    <td class="px-5 py-2.5 text-right">{{ fcfa($order->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-5 py-2.5 text-right text-espresso-700/65">
                        {{ $order->delivery_mode === 'delivery' ? 'Livraison' : 'Retrait au salon' }}
                    </td>
                    <td class="px-5 py-2.5 text-right">{{ fcfa($order->delivery_fee) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-5 py-3 text-right font-display text-lg text-espresso-900">Total</td>
                    <td class="px-5 py-3 text-right font-display text-xl font-semibold text-gold-600">{{ fcfa($order->total) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="space-y-6">
        <div class="card p-6">
            <h2 class="font-display text-lg font-semibold text-espresso-900">Cliente</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div><dt class="text-xs uppercase tracking-[0.12em] text-espresso-700/50">Nom</dt><dd class="text-espresso-900">{{ $order->customer_name }}</dd></div>
                <div><dt class="text-xs uppercase tracking-[0.12em] text-espresso-700/50">Téléphone</dt><dd><a href="tel:{{ $order->customer_phone }}" class="text-gold-600">{{ $order->customer_phone }}</a></dd></div>
                @if ($order->customer_email)
                    <div><dt class="text-xs uppercase tracking-[0.12em] text-espresso-700/50">E-mail</dt><dd>{{ $order->customer_email }}</dd></div>
                @endif
                @if ($order->delivery_mode === 'delivery')
                    <div><dt class="text-xs uppercase tracking-[0.12em] text-espresso-700/50">Adresse</dt><dd class="text-espresso-900">{{ $order->address }}</dd></div>
                @endif
                @if ($order->notes)
                    <div><dt class="text-xs uppercase tracking-[0.12em] text-espresso-700/50">Note</dt><dd class="text-espresso-700/80">{{ $order->notes }}</dd></div>
                @endif
            </dl>
        </div>

        <div class="card p-6">
            <h2 class="font-display text-lg font-semibold text-espresso-900">Statut</h2>
            <span class="mt-3 inline-block rounded-full px-3 py-1 text-xs font-medium ring-1 {{ $order->status_color }}">{{ $order->status_label }}</span>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4">
                @csrf @method('PATCH')
                <select name="status" class="field">
                    @foreach (\App\Models\Order::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn-dark mt-3 w-full py-2.5 text-xs">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>

@endsection
