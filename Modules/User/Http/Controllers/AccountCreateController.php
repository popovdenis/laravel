<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Exceptions\AlreadyExistsException;
use Modules\Base\Exceptions\InputException;
use Modules\Base\Http\Controllers\Controller;
use Modules\EventManager\Contracts\ManagerInterface;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Contracts\AccountManagementInterface;
use Modules\User\Data\CustomerData;
use Modules\User\Exceptions\CreateAccountException;
use Throwable;

class AccountCreateController extends Controller
{
    private ManagerInterface $eventManager;

    private AccountManagementInterface $accountManager;

    public function __construct(
        ManagerInterface $eventManager,
        AccountManagementInterface $accountManager
    )
    {
        $this->eventManager = $eventManager;
        $this->accountManager = $accountManager;
    }

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
        try {
            $customerData = CustomerData::fromRequest($request);

            $customer = $this->accountManager->createAccount($customerData, $customerData->password);

            $this->eventManager->dispatch(
                'customer_register_success',
                ['customer' => $customer, 'customer_data' => $customerData]
            );
        } catch (CreateAccountException | InputException | AlreadyExistsException $exception) {
            return redirect()->back()->withErrors(['error' => $exception->getMessage()])->withInput();
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])
                ->withInput();
        }

        $customer->password_plaint = $request->password;

        Auth::login($customer);

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
