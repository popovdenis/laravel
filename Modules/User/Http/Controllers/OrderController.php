<?php

namespace Modules\User\Http\Controllers;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\Controller;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = auth()->user()->orders;

        return view('user::orders.index', compact('orders'));
    }

    public function list(Request $request)
    {
        $orders = $request->user()->orders;

        return response()->json([
            'orders' => $orders
        ]);
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

    public function show()
    {
        return view('user::orders.show');
    }

    public function order(Order $order)
    {
        return response()->json([
            'order' => $order->load('purchasable', 'user'),
            'statusLabel' => $order->status->label(),
            'isInvoiced' => $order->isInvoiced(),
            'invoiceUrl' => $order->isInvoiced() ? $order->invoice->pdf_url : null,
            'planName' => $order->purchasable?->plan->name,
            'totalAmount' => $order->getFormattedPrice($order->total_amount),
            'tax' => $order->getFormattedPrice($order->total_amount * 0.1),
            'totalWithTax' => $order->getFormattedPrice($order->total_amount * 0.1 + $order->total_amount),
        ]);
    }
}
