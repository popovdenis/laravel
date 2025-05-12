<?php

namespace Modules\SubscriptionPlan\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subscriptionplan::index');
    }

    public function list(Request $request)
    {
        $user = $request->user();

        $activeSubscription = $user->getActiveSubscription();
        $currentPlanId = $activeSubscription?->plan_id;

        $plans = SubscriptionPlan::query()
            ->where('status', true)
            ->when($currentPlanId, fn($q) => $q->where('id', '!=', $currentPlanId))
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'user'            => $user,
            'plans'           => $plans,
            'activePlan'      => $activeSubscription?->plan,
            'isSubscribed'    => (bool) $activeSubscription,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subscriptionplan::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('subscriptionplan::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('subscriptionplan::edit');
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
