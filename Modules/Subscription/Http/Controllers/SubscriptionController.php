<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Subscription\Services\SubscriptionService;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('usersubscription::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usersubscription::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);

        $user = auth()->user();
        $paymentMethod = 'pm_card_visa'; // тестовый метод Stripe (подставной)
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);

        $user->newSubscription('default', 'price_1RJIH504fVTImIORseJmgDpt')->create($paymentMethod);

        $newPlan = SubscriptionPlan::findOrFail($request->plan_id);

        app(SubscriptionService::class)->syncSubscriptionForUser($user, $newPlan->id);

        return redirect()->route('profile.dashboard')->with('success', 'Your subscription plan has been updated.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();
        $currentPlanId = $user->userSubscription?->plan_id;
        $isSubscribed = $user->subscribed('default');

        $plans = SubscriptionPlan::where('status', true)
            ->when($currentPlanId, fn($q) => $q->where('id', '!=', $currentPlanId))
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        return view('usersubscription::show', compact('user', 'plans', 'isSubscribed'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('usersubscription::edit');
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
