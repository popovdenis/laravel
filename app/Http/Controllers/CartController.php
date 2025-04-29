<?php
namespace App\Http\Controllers;

use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        return view('cart.index', compact('cart'));
    }

    public function destroy(CartItem $item)
    {
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();
        return back()->with('success', 'The course has been removed form the cart');
    }
}
