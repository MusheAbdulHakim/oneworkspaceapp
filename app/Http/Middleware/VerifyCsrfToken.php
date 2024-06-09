<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'invoice/paytm/*',
        'plan-get-paytm-status',
        'invoice/mollie/*',
        '/plan/iyzipay/*',
        '/invoice/iyzipay/*',
        '/plan/aamarpay/*',
        '/invoice/aamarpay/*',
        '/course/iyzipay/*',
        '*course/mercado*',
        '/course/aamarpay/*',
        '/course/paytm*',
        '/roombooking/iyzipay/*',
        '/roombooking/aamarpay/*',
        '/plan/paytab/*',
        'plan-get-phonepe-status/*',
        '/invoice/phonepe/*',
        'course/phonepe/*',
        '*/get-payment-status/*',
        '*/cinetpay/*',
    ];
}
