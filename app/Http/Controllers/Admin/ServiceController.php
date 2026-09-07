<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.services.index', [
            'services' => Service::with('category')
                ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
                ->orderBy('service_category_id')
                ->orderBy('position')
                ->paginate(25)
                ->withQueryString(),
            'categories' => ServiceCategory::orderBy('position')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.services.form', [
            'service' => new Service(['duration' => 60, 'is_active' => true]),
            'categories' => ServiceCategory::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));
        $data['image'] = $this->storeImage($request) ?? null;

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Prestation créée.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', [
            'service' => $service,
            'categories' => ServiceCategory::orderBy('position')->get(),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validated($request);

        if ($image = $this->storeImage($request)) {
            $data['image'] = $image;
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Prestation mise à jour.');
    }

    public function destroy(Service $service)
    {
        if ($service->appointments()->exists()) {
            $service->update(['is_active' => false]);

            return back()->with('success', 'Prestation désactivée (des rendez-vous y sont liés).');
        }

        $service->delete();

        return back()->with('success', 'Prestation supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'service_category_id' => ['required', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0'],
            'price_max' => ['nullable', 'integer', 'gte:price'],
            'duration' => ['required', 'integer', 'min:10', 'max:600'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
        ], [], [
            'service_category_id' => 'catégorie',
            'name' => 'nom',
            'price' => 'tarif',
            'duration' => 'durée',
        ]) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'position' => (int) $request->input('position', 0),
        ];
    }

    private function storeImage(Request $request): ?string
    {
        $request->validate(['image' => ['nullable', 'image', 'max:4096']]);

        return $request->hasFile('image')
            ? $request->file('image')->store('services', 'public')
            : null;
    }
}
