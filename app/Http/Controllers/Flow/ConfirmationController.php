<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    public function store(Request $request)
    {
        // Здесь можно будет потом вызвать Stripe и т.д.

        // Очистим сессию, если нужно
        session()->forget([
            'flow.course_id',
            'flow.teacher_id',
            'flow.slot_id',
        ]);

        return redirect()->route('checkout.confirmed');
    }

    public function confirmed()
    {
        return view('flow.confirmed');
    }
}
