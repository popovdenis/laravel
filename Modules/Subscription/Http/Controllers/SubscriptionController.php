<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\EventManager\Contracts\ManagerInterface;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Subscription\Data\SubscriptionData;
use Modules\Subscription\Exceptions\SubscriptionValidationException;
use Modules\Subscription\Factories\SubscriptionQuoteFactory;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Throwable;

class SubscriptionController extends Controller
{
    /**
     * @var \Modules\Order\Contracts\OrderManagerInterface
     */
    private OrderManagerInterface $orderManager;
    /**
     * @var \Modules\Subscription\Factories\SubscriptionQuoteFactory
     */
    private SubscriptionQuoteFactory $quoteFactory;
    /**
     * @var ManagerInterface $eventManager
     */
    private ManagerInterface $eventManager;

    public function __construct(
        OrderManagerInterface $orderManager,
        SubscriptionQuoteFactory $quoteFactory,
        ManagerInterface $eventManager,
    )
    {
        $this->orderManager = $orderManager;
        $this->quoteFactory = $quoteFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subscription::index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $subscriptionData = SubscriptionData::fromRequest($request);

            $quote = $this->quoteFactory->create($subscriptionData);
            $order = $this->orderManager->place($quote);

            $this->eventManager->dispatch('save_subscription_order_after', ['order' => $order, 'quote' => $quote]);

            return redirect()->route('profile.dashboard')->with('success', 'Your have been subscribed successfully.');
        } catch (SubscriptionValidationException $exception) {
            $message = 'You don’t have an active payment method. ';
            $message .= '<a href=":link" class="underline hover:text-blue-800">Add a card</a> to continue.';

            return redirect()->back()
                ->withErrors(['error' => __($message, ['link' => route('profile.dashboard')])])
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
    public function show()
    {
        $user = auth()->user();

        $activeSubscription = $user->getActiveSubscription();
        $currentPlanId = $activeSubscription?->plan_id;

        $plans = SubscriptionPlan::query()
            ->where('status', true)
            ->when($currentPlanId, fn($q) => $q->where('id', '!=', $currentPlanId))
            ->orderBy('sort_order')
            ->get();

        return view('subscription::show', [
            'user'            => $user,
            'plans'           => $plans,
            'activePlan'      => $activeSubscription?->plan,
            'isSubscribed'    => (bool) $activeSubscription,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('subscription::edit');
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
