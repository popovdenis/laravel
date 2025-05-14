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

    public function handleCheckoutSessionCompleted(array $payload)
    {
        $session = $payload['data']['object'];

        $user = User::where('stripe_id', $session['customer'])->first();

        if ($user) {
            $user->markAsPaid();
        }

        return response()->json(['status' => 'ok']);
    }

    public function createSession(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $user = auth()->user();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price' => 'price_1RLV9o04fVTImIORRKU56dzp',
                'quantity' => 1,
            ]],
            'success_url' => route('stripecard::checkout.success'),
            'cancel_url' => route('stripecard::checkout.cancel'),
            'client_reference_id' => $user->id,//client_reference_id
            'metadata' => [
                'user_id' => $user->id,
                'email' => $user->email,
                'plan_id' => 2211223,
                'purchase_type' => 'one-time',
                'price_id' => 'price_1RLV9o04fVTImIORRKU56dzp'
            ],
        ]);

        return redirect($session->url);
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

        // TODO: call event to update subscription

        return redirect()->back()->with('success', 'Card has been saved successfully!');
    }

    public function detach(Request $request)
    {
        $user = $request->user();

        if ($user->hasDefaultPaymentMethod()) {
            $user->deletePaymentMethod($user->defaultPaymentMethod()->id);
        }

        // TODO: call event to cancel subscription

        return redirect()->back()->with('success', 'Card has been deleted successfully!');
    }
}
