<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\Controller;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->latest()
            ->withCount('items')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function store()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->getAuthIdentifier()]);

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        $order = Order::create(['user_id' => $user->getAuthIdentifier(), 'status' => 'pending']);

        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'itemable_id' => $cartItem->itemable_id,
                'itemable_type' => $cartItem->itemable_type,
                'quantity' => $cartItem->quantity,
            ]);
        }

        $cart->emptyCart();

        return redirect()->route('profile.orders.show', $order)->with('success', 'Order placed successfully');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return view('orders.show', compact('order'));
    }
}
