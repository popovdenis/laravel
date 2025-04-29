<?php
declare(strict_types=1);

namespace Modules\Stripe\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

/**
 * Class Subscription
 *
 * @package Modules\Stripe\Http\Controllers
 */
class SubscriptionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        $paymentMethod = 'pm_card_visa'; // тестовый метод Stripe (подставной)

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);

        $user->newSubscription('default', 'prod_SDjkIHDyz1jGFa')->create($paymentMethod);

        return redirect()->route('profile.dashboard')->with('success', 'Your subscription plan has been updated.');
    }
}
