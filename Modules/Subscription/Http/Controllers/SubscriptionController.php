<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subscription::index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('subscription::edit');
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
