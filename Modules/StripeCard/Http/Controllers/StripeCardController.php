<?php

namespace Modules\StripeCard\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

class StripeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('stripecard::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stripecard::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show(Request $request)
    {
        $intent = $request->user()->createSetupIntent();

        return view('stripecard::card', [
            'clientSecret' => $intent->client_secret,
        ]);
    }

    public function attach(Request $request)
    {
        $request->validate(['payment_method' => 'required|string']);

        $user = $request->user();
        if (! $user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        $user->addPaymentMethod($request->payment_method);
        $user->updateDefaultPaymentMethod($request->payment_method);

        return redirect()->back()->with('success', 'Card saved successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('stripecard::edit');
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
