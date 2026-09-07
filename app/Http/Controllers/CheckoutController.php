<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private readonly Cart $cart) {}

    public function create()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Votre panier est vide.');
        }

        return view('public.checkout', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Votre panier est vide.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'delivery_mode' => ['required', 'in:pickup,delivery'],
            'address' => ['nullable', 'required_if:delivery_mode,delivery', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'customer_name' => 'nom',
            'customer_phone' => 'téléphone',
            'address' => 'adresse de livraison',
        ]);

        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $fee = $this->cart->deliveryFee($data['delivery_mode']);

        $order = DB::transaction(function () use ($data, $items, $subtotal, $fee) {
            $order = Order::create([
                ...$data,
                'subtotal' => $subtotal,
                'delivery_fee' => $fee,
                'total' => $subtotal + $fee,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                /** @var Product $product */
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                $product->decrement('stock', min($item['quantity'], $product->stock));
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.confirmation', $order->reference);
    }

    public function confirmation(Order $order)
    {
        return view('public.order-confirmation', [
            'order' => $order->load('items'),
        ]);
    }
}
