<?php
declare(strict_types=1);

namespace App\Http\Middleware;

/**
 * Class VerifyCsrfToken
 *
 * @package App\Http\Middleware
 */
class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    protected $addHttpCookie = true;
    protected $except = [
        'stripe/webhook',
        'stripe/webhook/',
        '/stripe/webhook',
    ];
}
