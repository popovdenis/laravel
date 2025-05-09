<?php

namespace Modules\StripeCard\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StripeCard extends Component
{
    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View
    {
        $user = auth()->user();
        $intent = $user->createSetupIntent();
        $stripeKey = config('cashier.key');

        return view('stripecard::components.user-stripe-card', [
            'clientSecret' => $intent->client_secret,
            'stripeKey' => $stripeKey,
            'hasCard' => $user->hasDefaultPaymentMethod(),
            'card' => optional($user->defaultPaymentMethod())->card,
        ]);
    }
}
