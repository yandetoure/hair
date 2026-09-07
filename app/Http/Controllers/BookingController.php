<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\StaffMember;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(private readonly AvailabilityService $availability) {}

    public function create(Request $request)
    {
        return view('public.booking', [
            'categories' => ServiceCategory::with('activeServices')->orderBy('position')->get(),
            'staff' => StaffMember::where('is_active', true)->orderBy('position')->get(),
            'days' => $this->availability->openDays(),
            'selectedService' => $request->filled('service')
                ? Service::active()->where('slug', $request->string('service'))->first()
                : null,
        ]);
    }

    /** Créneaux disponibles (appelé en AJAX depuis la page de réservation). */
    public function slots(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'date' => ['required', 'date'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $date = Carbon::parse($data['date'])->startOfDay();

        return response()->json([
            'date' => $date->toDateString(),
            'label' => fr_date($date),
            'duration' => $service->duration_label,
            'slots' => $this->availability->slots($date, $service),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'staff_member_id' => ['nullable', 'exists:staff_members,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'service_id' => 'prestation',
            'date' => 'date',
            'time' => 'créneau',
            'customer_name' => 'nom',
            'customer_phone' => 'téléphone',
        ]);

        $service = Service::active()->findOrFail($data['service_id']);
        $start = Carbon::parse($data['date'].' '.$data['time']);

        if (! $this->availability->isSlotAvailable($start, $service)) {
            throw ValidationException::withMessages([
                'time' => "Ce créneau vient d'être réservé. Merci d'en choisir un autre.",
            ]);
        }

        $appointment = Appointment::create([
            'service_id' => $service->id,
            'staff_member_id' => $data['staff_member_id'] ?? null,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'starts_at' => $start,
            'ends_at' => $start->copy()->addMinutes($service->duration),
            'price' => $service->price,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('booking.confirmation', $appointment->reference);
    }

    public function confirmation(Appointment $appointment)
    {
        return view('public.booking-confirmation', [
            'appointment' => $appointment->load('service', 'staff'),
        ]);
    }
}
