<?php
namespace Modules\Base\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Modules\Base\Services\CustomerTimezone;

abstract class Controller extends BaseController
{
    protected CustomerTimezone $timezone;

    public function __construct(CustomerTimezone $timezone)
    {
        $this->timezone = $timezone;

        $this->middleware(function ($request, $next) {
            $this->timezone->setUser(auth()->user());
            return $next($request);
        });
    }
}
