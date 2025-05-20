<?php

namespace Modules\Booking\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Data\BookingData;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Booking\Exceptions\SlotUnavailableException;
use Modules\Booking\Factories\BookingQuoteFactory;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingScheduleManager;
use Modules\Booking\Models\ConfigProvider;
use Modules\Booking\Services\BookingSlotService;
use Modules\EventManager\Contracts\ManagerInterface;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Security\Enums\RequestType;
use Modules\Security\Exceptions\SecurityViolationException;
use Modules\Security\Models\AttemptRequestEvent;
use Modules\Security\Models\SecurityManager;
use Modules\Subscription\Exceptions\InsufficientCreditsException;
use Modules\User\Contracts\UserRepositoryInterface;
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
    private ManagerInterface        $eventManager;
    private ConfigProvider          $configProvider;
    private UserRepositoryInterface $userRepository;
    private BookingScheduleManager  $bookingScheduleManager;
    private BookingSlotService      $bookingSlotService;

    public function __construct(
        CustomerTimezone        $timezone,
        SecurityManager         $securityManager,
        BookingQuoteFactory     $bookingQuoteFactory,
        OrderManagerInterface   $orderManager,
        ManagerInterface        $eventManager,
        ConfigProvider          $configProvider,
        UserRepositoryInterface $userRepository,
        BookingScheduleManager  $bookingScheduleManager,
        BookingSlotService      $bookingSlotService,
        private                 $bookingRequestEvent = RequestType::BOOKING_ATTEMPT_REQUEST,
    )
    {
        parent::__construct($timezone);
        $this->securityManager        = $securityManager;
        $this->bookingQuoteFactory    = $bookingQuoteFactory;
        $this->orderManager           = $orderManager;
        $this->eventManager           = $eventManager;
        $this->configProvider         = $configProvider;
        $this->userRepository         = $userRepository;
        $this->bookingScheduleManager = $bookingScheduleManager;
        $this->bookingSlotService     = $bookingSlotService;
    }

    private function getFilters(Request $request): array
    {
        return [
            'level_id'    => $request->input('level_id'),
            'subject_ids' => $request->input('subject_ids', []),
            'type'        => $request->input('type'),
            'start_date'  => $request->input('start_date'),
            'end_date'    => $request->input('end_date'),
            'lesson_type' => $request->input('lesson_type', 'group'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $lessonType = $this->bookingSlotService->getDefaultLessonType();
        $visibleDatesCount = $this->bookingSlotService->getInitialDaysToShow();
        $startPreferredTime = $request->user()->getAttribute('preferred_start_time')?->format('H:i') ?? '00:00';
        $endPreferredTime = $request->user()->getAttribute('preferred_end_time')?->format('H:i') ?? '23:59';

        return view(
            'booking::index',
            compact('lessonType', 'visibleDatesCount', 'startPreferredTime', 'endPreferredTime')
        );
    }

    public function list(Request $request)
    {
        $slotsResponse = $this->bookingScheduleManager
            ->setFilters($this->getFilters($request))
            ->setStudent($request->user())
            ->getBookingScheduleSlots();

        return response()->json($slotsResponse);
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

            return response()->json([
                'success' => true,
                'booking_id' => $order->purchasable_id,
                'message' => 'Booking has been successfully created.'
            ]);
        } catch (BookingValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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

    public function preferredTime(Request $request)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $this->userRepository->savePreferredTime($request->user(), $startTime, $endTime);

        return response()->json(['success' => true, 'message' => 'Your preferred time is set.']);
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
