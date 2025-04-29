<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Modules\Base\Http\Controllers\Controller;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $subscriptionPlans = SubscriptionPlan::where('status', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id');

        return view('auth.register', compact('subscriptionPlans'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'subscription_plan_id'  => ['required', 'exists:subscription_plans,id'],
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user, $request->input('subscription_plan_id')));

        $user->password_plaint = $request->password;

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
