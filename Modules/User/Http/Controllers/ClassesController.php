<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\BookingRepositoryInterface;

class ClassesController extends Controller
{
    private BookingRepositoryInterface $bookingRepository;

    public function __construct(
        CustomerTimezone $timezone,
        BookingRepositoryInterface $bookingRepository
    )
    {
        parent::__construct($timezone);
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $type = $request->input('type', BookingRepositoryInterface::SCHEDULED_CLASSES);

        $classes = $this->bookingRepository->getUserBookingsByType($user, $type, 5);

        return view('user::classes.index', compact('classes', 'type'));
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
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
