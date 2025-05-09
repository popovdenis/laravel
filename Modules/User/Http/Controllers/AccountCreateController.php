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

    private AccountManagementInterface $accountManagement;

    public function __construct(
        ManagerInterface $eventManager,
        AccountManagementInterface $accountManagement
    )
    {
        $this->eventManager = $eventManager;
        $this->accountManagement = $accountManagement;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $subscriptionPlans = SubscriptionPlan::where('status', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id');
        $googleKey = config('services.google_maps.api_key');

        return view('user::account.register', compact('subscriptionPlans', 'googleKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $customerData = CustomerData::fromRequest($request);
dd($customerData);
            $customer = $this->accountManagement->createAccount($customerData, $customerData->password);

            $this->eventManager->dispatch(
                'customer_register_success',
                ['customer' => $customer, 'customer_data' => $customerData]
            );
            $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer);
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                return redirect()->back()
                    ->with('success',
                        sprintf('Thank you for registering with %s.
                         We will send you the confirmation email.
                         Please confirm before you logged in', $customer->name())
                    );
            } else {
                $customer->password_plaint = $request->password;
                Auth::login($customer);

                return redirect('/')->with('success', sprintf('Thank you for registering with %s.', $customer->getNameAttribute()));
            }
        } catch (CreateAccountException | InputException | AlreadyExistsException $exception) {
            return redirect()->back()->withErrors(['error' => $exception->getMessage()])->withInput();
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])
                ->withInput();
        }
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
