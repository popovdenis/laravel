<?php

namespace Modules\Booking\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Booking\Data\BookingData;
use Modules\Booking\Exceptions\SlotUnavailableException;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingInterface;
use Modules\Booking\Services\BookingManagementInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Throwable;

class BookingController extends Controller
{
    /**
     * @var \Modules\Booking\Services\BookingManagementInterface
     */
    private BookingManagementInterface $bookingManagement;

    public function __construct(BookingManagementInterface $bookingManagement)
    {
        $this->bookingManagement = $bookingManagement;
    }

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
    public function store(Request $request): RedirectResponse
    {
        try {
            $bookingData = BookingData::fromRequest($request);

            $booking = $this->bookingManagement->place($bookingData);

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

    protected function isValidPostRequest(Request $request)
    {
        return $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);
    }

    protected function _initBooking(Request $request): ?BookingInterface
    {
        $id = $request->input('booking_id');
        try {
            $booking = Booking::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        return $booking;
    }

    public function cancel(Request $request): RedirectResponse
    {
        if (!$errors = $this->isValidPostRequest($request)) {
            redirect()->back()->withErrors($errors)->withInput();
        }

        $booking = $this->_initBooking($request);
        if ($booking) {
            try {
                $this->bookingManagement->cancel($booking);
//                $this->messageManager->addSuccessMessage(__('You canceled the order.'));
            } catch (\Exception $exception) {
            }
        }

//        $booking = Booking::findOrFail($request->input('booking_id'));

        // Опционально: проверка, что текущий пользователь имеет право отменить
//        if ($booking->student_id !== auth()->id()) {
//            abort(403, 'Unauthorized');
//        }
//
//        $booking->delete(); // Или ->update(['status' => 'cancelled']) если soft delete не используется

        return redirect()->back()->with('success', 'Booking has been successfully cancelled.');
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
