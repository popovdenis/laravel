<?php

namespace App\Http\Controllers;

use App\Data\BookingData;
use App\Factories\BookingFactoryInterface;
use App\Models\Booking;
use App\Models\Enums\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PaymentFailedException;
use App\Exceptions\SlotUnavailableException;
use App\Services\Booking\BookingManagementInterface;
use Illuminate\Http\RedirectResponse;
use Throwable;

class BookingController extends Controller
{
    public function store(Request $request, BookingManagementInterface $bookingManagement): RedirectResponse
    {
        try {
            $bookingData = BookingData::fromRequest($request);

            $booking = $bookingManagement->submit($bookingData);

            return redirect()->back()->with('success', 'Booking has been successfully created.');
        } catch (SlotUnavailableException $e) {
            return redirect()->back()
                ->withErrors(['slot' => 'Selected time slot is not available. Please choose another time.'])
                ->withInput();
        } catch (PaymentFailedException $e) {
            return redirect()->back()
                ->withErrors(['payment' => 'Payment failed: ' . $e->getMessage()])
                ->withInput();
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])
                ->withInput();
        }
    }

    public function store2(Request $request)
    {
        $request->validate([
            'stream_id' => 'required|exists:streams,id',
            'slot_id'   => 'required|numeric|exists:schedule_timeslots,id',
        ]);

        $studentId = Auth::id();

        $exists = Booking::where('student_id', $studentId)
            ->where('stream_id', $request->stream_id)
            ->where('schedule_timeslot_id', $request->slot_id)
            ->exists();

        if (! $exists) {
            Booking::create([
                'student_id'           => $studentId,
                'stream_id'            => $request->stream_id,
                'schedule_timeslot_id' => $request->slot_id,
                'status'               => BookingStatus::PENDING,
            ]);
        }

        return back()->with('success', 'Booking successful!');
    }
}
