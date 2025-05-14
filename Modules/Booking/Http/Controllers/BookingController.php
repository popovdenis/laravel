<?php

namespace Modules\Booking\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Exceptions\AlreadyExistsException;
use Modules\Base\Http\Controllers\Controller;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Data\BookingData;
use Modules\Booking\Exceptions\SlotUnavailableException;
use Modules\Booking\Factories\BookingQuoteFactory;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\ConfigProvider;
use Modules\EventManager\Contracts\ManagerInterface;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Security\Enums\RequestType;
use Modules\Security\Exceptions\SecurityViolationException;
use Modules\Security\Models\AttemptRequestEvent;
use Modules\Security\Models\SecurityManager;
use Modules\Subscription\Exceptions\InsufficientCreditsException;
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
    /**
     * @var \Modules\EventManager\Contracts\ManagerInterface
     */
    private ManagerInterface $eventManager;
    private ConfigProvider $configProvider;

    public function __construct(
        CustomerTimezone $timezone,
        SecurityManager $securityManager,
        BookingQuoteFactory $bookingQuoteFactory,
        OrderManagerInterface $orderManager,
        ManagerInterface $eventManager,
        ConfigProvider $configProvider,
        private $bookingRequestEvent = RequestType::BOOKING_ATTEMPT_REQUEST,
    )
    {
        parent::__construct($timezone);
        $this->securityManager = $securityManager;
        $this->bookingQuoteFactory = $bookingQuoteFactory;
        $this->orderManager = $orderManager;
        $this->eventManager = $eventManager;
        $this->configProvider = $configProvider;
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
    public function store(Request $request)
    {
        try {
            $bookingData = BookingData::fromRequest($request);

            $securityKey = $this->securityManager->generateEventKey([$bookingData->student->id, $bookingData->slotId]);
            $this->securityManager->performSecurityCheck($this->bookingRequestEvent, $securityKey);

            $quote = $this->bookingQuoteFactory->create($bookingData);
            $order = $this->orderManager->place($quote);

            $this->markBookingReindexRequired();

            $this->eventManager->dispatch('save_booking_order_after', ['order' => $order, 'quote' => $quote]);

            return response()->json(['success' => true, 'message' => 'Booking has been successfully created.']);
        } catch (AlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The selected time slot hasl already been chosen. Please choose another time.',
            ], 422);
        } catch (SlotUnavailableException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Selected time slot is not available. Please choose another time.',
            ], 422);
        } catch (InsufficientCreditsException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You don’t have enough credits to book a class. Please top-up credits or upgrade your plan.',
            ], 422);
        } catch (PaymentFailedException $e) {
            return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 422);
        } catch (SecurityViolationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 422);
        }
    }

    private function markBookingReindexRequired(): void
    {
        $this->configProvider->markBookingReindex(true);
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

                $this->eventManager->dispatch('cancel_booking_order_after', ['order' => $order, 'quote' => $quote]);
            } catch (Throwable $e) {
                report($e);

                return redirect()->back()
                    ->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])
                    ->withInput();
            }
        }

        return redirect()->back()->with('success', 'You canceled the booking.');
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
