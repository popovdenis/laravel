<?php

namespace Modules\Booking\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Booking\Data\BookingData;
use Modules\Booking\Exceptions\SlotUnavailableException;
use Modules\Booking\Services\BookingManagementInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Throwable;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('booking::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('booking::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BookingManagementInterface $bookingManagement): RedirectResponse
    {
        try {
            $bookingData = BookingData::fromRequest($request);

            $booking = $bookingManagement->submit($bookingData);

            return redirect()->back()->with('success', 'Booking has been successfully created.');
        } catch (AlreadyExistsException $e) {
            return redirect()->back()
                ->withErrors(['slot' => 'The selected time slot hasl already been chosen. Please choose another time.'])
                ->withInput();
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

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('booking::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('booking::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
