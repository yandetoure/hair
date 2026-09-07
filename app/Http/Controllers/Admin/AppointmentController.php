<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with('service', 'staff')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(fn ($sub) => $sub->where('customer_name', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term)
                    ->orWhere('reference', 'like', $term));
            })
            ->when($request->filled('from'), fn ($q) => $q->whereDate('starts_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('starts_at', '<=', $request->date('to')))
            ->orderByDesc('starts_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    /** Vue agenda : jour, semaine ou mois. */
    public function agenda(Request $request)
    {
        $view = in_array($request->query('vue'), ['jour', 'semaine', 'mois']) ? $request->query('vue') : 'semaine';
        $date = $request->filled('date') ? Carbon::parse($request->query('date')) : Carbon::today();

        [$from, $to] = match ($view) {
            'jour' => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'mois' => [$date->copy()->startOfMonth()->startOfWeek(), $date->copy()->endOfMonth()->endOfWeek()],
            default => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
        };

        $appointments = Appointment::with('service', 'staff')
            ->whereBetween('starts_at', [$from, $to])
            ->orderBy('starts_at')
            ->get();

        return view('admin.appointments.agenda', [
            'view' => $view,
            'date' => $date,
            'from' => $from,
            'to' => $to,
            'appointments' => $appointments,
            'byDay' => $appointments->groupBy(fn ($a) => $a->starts_at->toDateString()),
        ]);
    }

    public function create()
    {
        return view('admin.appointments.form', [
            'appointment' => new Appointment(['starts_at' => now()->addHour()->startOfHour()]),
            'services' => Service::active()->orderBy('name')->get(),
            'staff' => StaffMember::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $service = Service::findOrFail($data['service_id']);
        $start = Carbon::parse($data['date'].' '.$data['time']);

        Appointment::create([
            ...collect($data)->except(['date', 'time'])->all(),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addMinutes($service->duration),
            'price' => $data['price'] ?? $service->price,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Rendez-vous enregistré.');
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.form', [
            'appointment' => $appointment,
            'services' => Service::active()->orderBy('name')->get(),
            'staff' => StaffMember::where('is_active', true)->get(),
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $this->validated($request);
        $service = Service::findOrFail($data['service_id']);
        $start = Carbon::parse($data['date'].' '.$data['time']);

        $appointment->update([
            ...collect($data)->except(['date', 'time'])->all(),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addMinutes($service->duration),
            'price' => $data['price'] ?? $service->price,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Rendez-vous mis à jour.');
    }

    public function status(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Appointment::STATUSES))],
        ]);

        $appointment->update($data);

        return back()->with('success', 'Statut du rendez-vous mis à jour.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return back()->with('success', 'Rendez-vous supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'staff_member_id' => ['nullable', 'exists:staff_members,id'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'price' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:'.implode(',', array_keys(Appointment::STATUSES))],
            'notes' => ['nullable', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'service_id' => 'prestation',
            'customer_name' => 'nom du client',
            'customer_phone' => 'téléphone',
        ]);
    }
}
