<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\StaffMember;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home', [
            'categories' => ServiceCategory::with('activeServices')->orderBy('position')->get(),
            'featuredServices' => Service::active()->with('category')->where('is_featured', true)->take(6)->get(),
            'featuredProducts' => Product::active()->where('is_featured', true)->take(4)->get(),
            'testimonials' => Testimonial::where('is_published', true)->latest()->take(3)->get(),
            'staff' => StaffMember::where('is_active', true)->orderBy('position')->take(4)->get(),
            'gallery' => GalleryItem::published()->orderBy('position')->take(8)->get(),
            'offers' => Offer::active()->orderBy('position')->take(3)->get(),
            'posts' => Post::published()->orderByDesc('published_at')->take(3)->get(),
        ]);
    }

    public function salon()
    {
        return view('public.salon', [
            'staff' => StaffMember::where('is_active', true)->orderBy('position')->get(),
            'testimonials' => Testimonial::where('is_published', true)->latest()->take(6)->get(),
            'gallery' => GalleryItem::published()->orderBy('position')->take(6)->get(),
        ]);
    }

    public function contact()
    {
        return view('public.contact');
    }
}
