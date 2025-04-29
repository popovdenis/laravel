<?php

namespace Modules\BookingCreditHistory\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

class BookingCreditHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bookingcredithistory::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bookingcredithistory::create');
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
        return view('bookingcredithistory::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('bookingcredithistory::edit');
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
