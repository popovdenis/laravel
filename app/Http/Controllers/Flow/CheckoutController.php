<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleTimeslot;
use App\Models\User;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $rawSlots = $request->input('selected_slots');
        if (is_string($rawSlots)) {
            $decoded = json_decode($rawSlots, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['selected_slots' => $decoded]);
            }
        }

        $request->validate([
            'selected_slots' => 'required|array|min:1',
        ]);

        $teacherId = session('teacher_id');

        if (!$teacherId) {
            return redirect()->route('flow.selectTeacher.index');
        }

        session(['slot_id' => $request->selected_slots]);

        return redirect()->route('flow.checkout.show');
    }

    public function show()
    {
        $teacherId = session('teacher_id');
        $slotIds = session('slot_id');

        if (!$teacherId || !$slotIds || !is_array($slotIds)) {
            return redirect()->route('flow.selectTeacher.index');
        }

        $teacher = User::findOrFail($teacherId);
        $slots = ScheduleTimeslot::whereIn('id', $slotIds)->get();

        return view('flow.checkout', compact('teacher', 'slots'));
    }
}
