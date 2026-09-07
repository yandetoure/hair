@extends('layouts.public')

@section('title', 'Finaliser ma commande — '.config('salon.name'))

@section('content')

    <section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
        <h1 class="font-display text-5xl font-semibold text-espresso-900">Finaliser ma commande</h1>
        <p class="mt-3 text-espresso-700/80">Aucun paiement en ligne : vous réglez au retrait ou à la livraison.</p>

        <form method="POST" action="{{ route('checkout.store') }}" class="mt-10 grid gap-10 lg:grid-cols-[1fr_380px]">
            @csrf

            <div class="space-y-8">
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-semibold text-espresso-900">Vos coordonnées</h2>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="label" for="customer_name">Nom & prénom *</label>
                            <input type="text" name="customer_name" id="customer_name" class="field" required value="{{ old('customer_name') }}">
                        </div>
                        <div>
                            <label class="label" for="customer_phone">Téléphone *</label>
                            <input type="tel" name="customer_phone" id="customer_phone" class="field" required value="{{ old('customer_phone') }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label" for="customer_email">E-mail (facultatif)</label>
                            <input type="email" name="customer_email" id="customer_email" class="field" value="{{ old('customer_email') }}">
                        </div>
                    </div>
                </div>

                <div class="card p-8">
                    <h2 class="font-display text-2xl font-semibold text-espresso-900">Mode de réception</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            ['pickup', 'Retrait au salon', 'Gratuit — '.config('salon.address')],
                            ['delivery', 'Livraison à Abidjan', fcfa(config('salon.delivery_fee')).' — offerte dès '.fcfa(config('salon.free_delivery_from'))],
                        ] as [$value, $title, $desc])
                            <label class="delivery-option flex cursor-pointer gap-3 rounded-xl border border-espresso-900/12 bg-white p-5 transition hover:border-gold-500">
                                <input type="radio" name="delivery_mode" value="{{ $value }}" class="mt-1 accent-[#c19a3c]"
                                       @checked(old('delivery_mode', 'pickup') === $value)>
                                <span>
                                    <span class="block font-medium text-espresso-900">{{ $title }}</span>
                                    <span class="mt-0.5 block text-xs text-espresso-700/65">{{ $desc }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-6" id="address-block">
                        <label class="label" for="address">Adresse de livraison</label>
                        <textarea name="address" id="address" rows="3" class="field"
                                  placeholder="Quartier, rue, repère, étage…">{{ old('address') }}</textarea>
                    </div>

                    <div class="mt-6">
                        <label class="label" for="notes">Note pour l'équipe (facultatif)</label>
                        <textarea name="notes" id="notes" rows="2" class="field" placeholder="Heure de passage souhaitée…">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <aside class="lg:sticky lg:top-28 lg:self-start">
                <div class="card p-6">
                    <h2 class="font-display text-2xl font-semibold text-espresso-900">Votre commande</h2>

                    <ul class="mt-5 divide-y divide-espresso-900/8 text-sm">
                        @foreach ($items as $item)
                            <li class="flex justify-between gap-4 py-3">
                                <span class="text-espresso-700/85">
                                    {{ $item['product']->name }}
                                    <span class="text-espresso-700/50">× {{ $item['quantity'] }}</span>
                                </span>
                                <span class="whitespace-nowrap font-medium text-espresso-900">{{ fcfa($item['line_total']) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <dl class="mt-5 space-y-2 border-t border-espresso-900/8 pt-5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-espresso-700/65">Sous-total</dt>
                            <dd class="font-medium text-espresso-900">{{ fcfa($subtotal) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-espresso-700/65">Livraison</dt>
                            <dd class="font-medium text-espresso-900" id="fee-line">0 FCFA</dd>
                        </div>
                    </dl>

                    <div class="mt-5 flex items-center justify-between border-t border-espresso-900/8 pt-5">
                        <span class="font-display text-lg text-espresso-900">Total</span>
                        <span class="font-display text-2xl font-semibold text-gold-600" id="total-line">{{ fcfa($subtotal) }}</span>
                    </div>

                    <button type="submit" class="btn-gold mt-6 w-full py-4">Envoyer ma commande</button>
                    <p class="mt-3 text-center text-xs text-espresso-700/55">Nous vous appelons pour confirmer la disponibilité.</p>
                </div>
            </aside>
        </form>
    </section>

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const fee = {{ (int) config('salon.delivery_fee') }};
    const freeFrom = {{ (int) config('salon.free_delivery_from') }};

    function format(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount).replace(/ | /g, ' ') + ' {{ config('salon.currency') }}';
    }

    function refresh() {
        const mode = document.querySelector('input[name="delivery_mode"]:checked')?.value ?? 'pickup';
        const applied = mode === 'delivery' && subtotal < freeFrom ? fee : 0;

        document.getElementById('fee-line').textContent = mode === 'delivery' ? format(applied) : 'Retrait — gratuit';
        document.getElementById('total-line').textContent = format(subtotal + applied);
        document.getElementById('address-block').classList.toggle('hidden', mode !== 'delivery');

        document.querySelectorAll('.delivery-option').forEach((node) => {
            const on = node.querySelector('input').checked;
            node.classList.toggle('border-gold-500', on);
            node.classList.toggle('bg-gold-500/10', on);
        });
    }

    document.querySelectorAll('input[name="delivery_mode"]').forEach((input) => input.addEventListener('change', refresh));
    refresh();
</script>
@endpush

@endsection
