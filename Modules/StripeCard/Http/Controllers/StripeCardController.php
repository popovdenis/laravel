<?php

namespace Modules\StripeCard\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\User\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('stripecard::checkout');
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

    public function handleCheckoutSessionCompleted(array $payload)
    {
        $session = $payload['data']['object'];

        $user = User::where('stripe_id', $session['customer'])->first();

        if ($user) {
            // Активируем доступ, создаём заказ и т.п.
            $user->markAsPaid(); // твоя кастомная логика
        }

        return response()->json(['status' => 'ok']);
    }

    public function createSession(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret')); // или config('services.stripe.secret')

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => 1000,
                    'product_data' => [
                        'name' => '1 Month Access',
                    ],
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('stripecard::checkout.success'),
            'cancel_url' => route('stripecard::checkout.cancel'),
        ]);

        return redirect($session->url);
    }

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
