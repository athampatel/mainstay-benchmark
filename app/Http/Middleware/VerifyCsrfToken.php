<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'sign-up',
        'sign-in',
        'forgot-password',
        'reset-password',
        'confirm-password',
        'logout',
        'email/verification-notification',
        'admin/login/submit',
        'admin/login',
        'admin/password/reset',
        'admin/password/reset/submit',
        'get-new-bottom-notifications'
    ];
}
