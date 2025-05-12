<?php

namespace Modules\User\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Modules\Base\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::profile.dashboard');
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $activeSubscription = $user->getActiveSubscription();
        $subscriptionPlan = $activeSubscription ? $activeSubscription->plan : null;

        $credits = $user?->credit_balance ?? 0;
        $size = $request->get('size', 'base');

        return response()->json([
            'user' => $user,
            'subscriptionPlan' => $subscriptionPlan,
            'creditsData' => [
                'credits' => $credits,
                'size' => $this->textSize($size),
                'color' => $this->color($credits)
            ],
        ]);
    }

    public function credits(Request $request)
    {
        $user = $request->user();
        $credits = $user?->credit_balance ?? 0;
        $size = $request->get('size', 'base');

        return response()->json([
            'credits' => $credits,
            'size' => $this->textSize($size),
            'color' => $this->color($credits)
        ]);
    }

    public function color($credits): string
    {
        return match (true) {
            $credits === 0 => 'text-rose-500',
            $credits < 5 => 'text-amber-500',
            default => 'text-green-600',
        };
    }

    public function textSize($size): string
    {
        return match ($size) {
            'sm' => 'text-sm font-normal',
            'lg' => 'text-lg font-semibold',
            default => '',
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        return view('user::profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('user::profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
