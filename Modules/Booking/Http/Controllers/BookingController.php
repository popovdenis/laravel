<?php

namespace Modules\Booking\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Booking\Data\BookingData;
use Modules\Booking\Exceptions\SlotUnavailableException;
use Modules\Booking\Factories\BookingQuoteFactory;
use Modules\Booking\Models\Booking;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Security\Enums\RequestType;
use Modules\Security\Exceptions\SecurityViolationException;
use Modules\Security\Models\AttemptRequestEvent;
use Modules\Security\Models\SecurityManager;
use Throwable;

class BookingController extends Controller
{
    /**
     * @var \Modules\Security\Models\SecurityManager
     */
    private SecurityManager $securityManager;
    /**
     * @var \Modules\Booking\Factories\BookingQuoteFactory
     */
    private BookingQuoteFactory $bookingQuoteFactory;
    /**
     * @var \Modules\Order\Contracts\OrderManagerInterface
     */
    private OrderManagerInterface $orderManager;

    public function __construct(
        SecurityManager $securityManager,
        BookingQuoteFactory $bookingQuoteFactory,
        OrderManagerInterface $orderManager,
        private $bookingRequestEvent = RequestType::BOOKING_ATTEMPT_REQUEST,
    )
    {
        $this->securityManager = $securityManager;
        $this->bookingQuoteFactory = $bookingQuoteFactory;
        $this->orderManager = $orderManager;
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

            $securityKey = $this->securityManager->generateEventKey([$bookingData->student->id, $bookingData->slotId]);
            $this->securityManager->performSecurityCheck($this->bookingRequestEvent, $securityKey);

            $quote = $this->bookingQuoteFactory->create($bookingData);
            $order = $this->orderManager->place($quote);

//            $this->_eventManager->dispatch('booking_submit_all_after', ['booking' => $booking]);

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
        } catch (SecurityViolationException $e) {
            return redirect()->back()->withErrors(['slot' => $e->getMessage()])->withInput();
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])
                ->withInput();
        }
    }

    public function cancel(Request $request): RedirectResponse
    {
        if (!$errors = $this->isValidPostRequest($request)) {
            redirect()->back()->withErrors($errors)->withInput();
        }

        $booking = $this->_initBooking($request);
        if ($booking) {
            try {
                $quote = $this->bookingQuoteFactory->create(BookingData::fromModel($booking));
                $order = $this->orderManager->findOrderByEntity($booking);
                $order->setQuote($quote);
                $order->setPayment($quote->getPayment());
                $this->orderManager->cancel($order);
//                $this->messageManager->addSuccessMessage(__('You canceled the order.'));
            } catch (\Exception $exception) {
            }
        }

        return redirect()->back()->with('success', 'Booking has been successfully cancelled.');
    }

    protected function isValidPostRequest(Request $request)
    {
        return $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);
    }

    protected function _initBooking(Request $request): ?PurchasableInterface
    {
        $id = $request->input('booking_id');
        try {
            $booking = Booking::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        return $booking;
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
