<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Subscription\Data\SubscriptionData;
use Modules\Subscription\Factories\SubscriptionQuoteFactory;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;

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

    public function __construct(
        OrderManagerInterface $orderManager,
        SubscriptionQuoteFactory $quoteFactory,
    )
    {
        $this->orderManager = $orderManager;
        $this->quoteFactory = $quoteFactory;
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
        dd($user = auth()->user());
        $subscriptionData = SubscriptionData::fromRequest($request);

        $quote = $this->quoteFactory->create($subscriptionData);
        $order = $this->orderManager->place($quote);

        return redirect()->route('profile.dashboard')->with('success', 'Your subscription plan has been updated.');
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
