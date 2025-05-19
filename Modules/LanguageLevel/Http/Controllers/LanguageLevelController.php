<?php

namespace Modules\LanguageLevel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Models\BookingScheduleManager;
use Modules\Booking\Services\BookingSlotService;
use Modules\LanguageLevel\Models\LanguageLevel;

class LanguageLevelController extends Controller
{

    public function index()
    {
        return view('languagelevel::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('languagelevel::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show(string $slug)
    {
        $level = LanguageLevel::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('levels.show', compact('level'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('languagelevel::edit');
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
