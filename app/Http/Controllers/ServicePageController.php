<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;

class ServicePageController extends Controller
{
    public function index()
    {
        return view('public.services', [
            'categories' => ServiceCategory::with(['activeServices'])->orderBy('position')->get(),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('public.service-show', [
            'service' => $service->load('category'),
            'related' => Service::active()
                ->where('service_category_id', $service->service_category_id)
                ->whereKeyNot($service->id)
                ->take(3)
                ->get(),
        ]);
    }
}
