<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Base\Http\Controllers\Controller;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;
use Illuminate\Validation\Rules;

class AccountCreateController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $subscriptionPlans = SubscriptionPlan::where('status', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id');

        return view('user::account.register', compact('subscriptionPlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
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

        return redirect(route('profile.dashboard', absolute: false));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::account.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::account.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}
}
