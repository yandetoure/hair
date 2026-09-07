<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class Cart
{
    private const KEY = 'cart';

    /** @return array<int, int> product_id => quantité */
    public function raw(): array
    {
        return Session::get(self::KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = $this->raw();
        $items[$product->id] = min(
            max(1, ($items[$product->id] ?? 0) + $quantity),
            max(1, $product->stock)
        );

        Session::put(self::KEY, $items);
    }

    public function update(Product $product, int $quantity): void
    {
        $items = $this->raw();

        if ($quantity < 1) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = min($quantity, max(1, $product->stock));
        }

        Session::put(self::KEY, $items);
    }

    public function remove(Product $product): void
    {
        $items = $this->raw();
        unset($items[$product->id]);
        Session::put(self::KEY, $items);
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
    }

    /** @return Collection<int, array{product: Product, quantity: int, line_total: int}> */
    public function items(): Collection
    {
        $raw = $this->raw();

        if (empty($raw)) {
            return collect();
        }

        return Product::whereIn('id', array_keys($raw))->get()
            ->map(fn (Product $product) => [
                'product' => $product,
                'quantity' => (int) $raw[$product->id],
                'line_total' => (int) $raw[$product->id] * $product->price,
            ])
            ->values();
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function subtotal(): int
    {
        return (int) $this->items()->sum('line_total');
    }

    public function deliveryFee(string $mode = 'pickup'): int
    {
        if ($mode !== 'delivery') {
            return 0;
        }

        return $this->subtotal() >= (int) config('salon.free_delivery_from')
            ? 0
            : (int) config('salon.delivery_fee');
    }

    public function total(string $mode = 'pickup'): int
    {
        return $this->subtotal() + $this->deliveryFee($mode);
    }

    public function isEmpty(): bool
    {
        return empty($this->raw());
    }
}
