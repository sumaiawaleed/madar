<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('appointments.index', [
            'upcomingByDay' => $user->appointments()->with('client')->upcoming()->get()
                ->groupBy(fn (Appointment $appointment): string => $appointment->scheduled_on->toDateString()),
            'past' => $user->appointments()->with('client')->past()->limit(8)->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('appointments.form', [
            'appointment' => new Appointment(['scheduled_on' => today()->addDay(), 'starts_at' => '10:00', 'duration_minutes' => 30, 'type' => Appointment::TYPES[0]]),
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function store(AppointmentRequest $request): RedirectResponse
    {
        $request->user()->appointments()->create([...$request->validated(), 'duration_minutes' => $request->validated('duration_minutes') ?? 30]);

        return redirect()->route('appointments.index')->with('status', 'تم حفظ الموعد');
    }

    public function edit(Request $request, Appointment $appointment): View
    {
        Gate::authorize('update', $appointment);

        return view('appointments.form', [
            'appointment' => $appointment,
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function update(AppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        Gate::authorize('update', $appointment);

        $appointment->update([...$request->validated(), 'duration_minutes' => $request->validated('duration_minutes') ?? 30]);

        return redirect()->route('appointments.index')->with('status', 'تم حفظ الموعد');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        Gate::authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')->with('status', 'تم الحذف');
    }
}
