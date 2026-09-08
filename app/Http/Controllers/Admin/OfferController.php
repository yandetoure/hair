<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    public function index()
    {
        return view('admin.offers.index', ['offers' => Offer::orderBy('position')->get()]);
    }

    public function create()
    {
        return view('admin.offers.form', ['offer' => new Offer(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));

        Offer::create($data);

        return redirect()->route('admin.offers.index')->with('success', 'Forfait créé.');
    }

    public function edit(Offer $offer)
    {
        return view('admin.offers.form', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $offer->update($this->validated($request));

        return redirect()->route('admin.offers.index')->with('success', 'Forfait mis à jour.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return back()->with('success', 'Forfait supprimé.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0'],
            'compare_price' => ['nullable', 'integer', 'gte:price'],
            'duration' => ['nullable', 'integer', 'min:0', 'max:900'],
            'includes' => ['nullable', 'string', 'max:2000'],
            'position' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:6144'],
        ], [], ['name' => 'nom', 'price' => 'tarif']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('offers', 'public');
        } else {
            unset($data['image']);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['position'] = (int) $request->input('position', 0);

        return $data;
    }
}
