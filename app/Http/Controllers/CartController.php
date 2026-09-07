<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly Cart $cart) {}

    public function index()
    {
        return view('public.cart', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('error', 'Ce produit est momentanément en rupture de stock.');
        }

        $this->cart->add($product, max(1, (int) $request->input('quantity', 1)));

        return redirect()->route('cart.index')->with('success', $product->name.' a été ajouté au panier.');
    }

    public function update(Request $request, Product $product)
    {
        $this->cart->update($product, (int) $request->input('quantity', 1));

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product);

        return back()->with('success', 'Produit retiré du panier.');
    }
}
