<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Product;
use App\Services\ReportService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(ReportService $reports)
    {
        $today = [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
        $week = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $month = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        $lastMonth = [
            Carbon::now()->subMonthNoOverflow()->startOfMonth(),
            Carbon::now()->subMonthNoOverflow()->endOfMonth(),
        ];

        return view('admin.dashboard', [
            'today' => $reports->kpis(...$today),
            'week' => $reports->kpis(...$week),
            'month' => $reports->kpis(...$month),
            'lastMonth' => $reports->kpis(...$lastMonth),
            'occupancyWeek' => $reports->occupancy(...$week),
            'series' => $reports->series(Carbon::now()->subDays(13)->startOfDay(), Carbon::today()->endOfDay()),
            'topServices' => $reports->topServices(...$month),
            'topProducts' => $reports->topProducts(...$month),
            'agendaToday' => Appointment::with('service', 'staff')
                ->whereDate('starts_at', Carbon::today())
                ->orderBy('starts_at')
                ->get(),
            'upcoming' => Appointment::with('service')
                ->where('starts_at', '>', now())
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('starts_at')
                ->take(6)
                ->get(),
            'pendingOrders' => Order::with('items')->where('status', 'pending')->latest()->take(6)->get(),
            'lowStock' => Product::active()->where('stock', '<=', 3)->orderBy('stock')->take(5)->get(),
        ]);
    }
}
