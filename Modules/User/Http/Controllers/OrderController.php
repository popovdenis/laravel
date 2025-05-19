<?php

namespace Modules\User\Http\Controllers;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\Controller;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = auth()->user()->orders()
                        ->latest()
                        ->paginate(10);

        return view('user::orders.index', compact('orders'));
    }

    public function store()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->getAuthIdentifier()]);

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        $order = Order::create(['user_id' => $user->getAuthIdentifier(), 'status' => 'pending']);


        $cart->emptyCart();

        return redirect()->route('profile.orders.show', $order)->with('success', 'Order placed successfully');
    }

    public function show(Order $order)
    {
        return view('user::orders.show', [
            'order' => $order->load('purchasable', 'user'),
        ]);
    }
}
