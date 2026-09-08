<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\JournalController as AdminJournalController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/le-salon', [HomeController::class, 'salon'])->name('salon');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/prestations', [ServicePageController::class, 'index'])->name('services.index');
Route::get('/prestations/{service:slug}', [ServicePageController::class, 'show'])->name('services.show');

Route::get('/realisations', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/forfaits', [OfferController::class, 'index'])->name('offers.index');
Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
Route::get('/journal/{post:slug}', [JournalController::class, 'show'])->name('journal.show');

Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
Route::get('/boutique/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/commande', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/commande', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/commande/{order:reference}/merci', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

Route::get('/rendez-vous', [BookingController::class, 'create'])->name('booking.create');
Route::get('/rendez-vous/creneaux', [BookingController::class, 'slots'])->name('booking.slots');
Route::post('/rendez-vous', [BookingController::class, 'store'])->name('booking.store');
Route::get('/rendez-vous/{appointment:reference}/confirmation', [BookingController::class, 'confirmation'])
    ->name('booking.confirmation');

/*
|--------------------------------------------------------------------------
| Espace de gestion
|--------------------------------------------------------------------------
*/
Route::get('/connexion', [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/connexion', [LoginController::class, 'store'])->middleware('guest');
Route::post('/deconnexion', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/agenda', [AdminAppointmentController::class, 'agenda'])->name('appointments.agenda');
    Route::resource('appointments', AdminAppointmentController::class)->except(['show']);
    Route::patch('/appointments/{appointment}/statut', [AdminAppointmentController::class, 'status'])
        ->name('appointments.status');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/statut', [AdminOrderController::class, 'status'])->name('orders.status');

    Route::resource('services', AdminServiceController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('staff', AdminStaffController::class)->except(['show']);
    Route::resource('gallery', AdminGalleryController::class)->except(['show'])->parameters(['gallery' => 'item']);
    Route::resource('offers', AdminOfferController::class)->except(['show']);
    Route::resource('journal', AdminJournalController::class)->except(['show'])->parameters(['journal' => 'post']);

    Route::get('/rapports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/rapports/impression', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/rapports/export', [ReportController::class, 'export'])->name('reports.export');
});
