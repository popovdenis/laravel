<?php
declare(strict_types=1);

namespace Modules\UserSubscription\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class CurrentUserSubscription
 *
 * @package Modules\UserSubscription\View\Components
 */
class CurrentUserSubscription extends Component
{
    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View
    {
        $user = auth()->user();
        $activeSubscription = $user->getActiveSubscription();
        $subscriptionPlan = $activeSubscription ? $activeSubscription->plan : null;

        return view('usersubscription::components.current-user-subscription', compact('subscriptionPlan'));
    }
}
