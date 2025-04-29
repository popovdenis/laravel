<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

class ZoomSignatureController extends Controller
{
    public function generate(Request $request)
    {
        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');
        $meetingNumber = $request->query('meetingNumber');
        $role = $request->query('role', 0);

        $signature = ZoomService::generateSignature(
            $sdkKey,
            $sdkSecret,
            $meetingNumber,
            $role
        );

        return response()->json([
            'signature' => $signature,
            'sdkKey' => $sdkKey,
        ]);
    }
}
