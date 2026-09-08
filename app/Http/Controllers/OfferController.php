<?php

namespace App\Http\Controllers;

use App\Models\Offer;

class OfferController extends Controller
{
    public function index()
    {
        return view('public.offers', [
            'offers' => Offer::active()->orderBy('position')->get(),
        ]);
    }
}
