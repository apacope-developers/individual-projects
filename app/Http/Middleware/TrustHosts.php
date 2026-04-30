<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * The trusted hosts for your application.
     *
     * @var array<int, string>
     */
    protected $hosts = [
        'localhost',
        '127.0.0.1',
        '127.0.0.1:8001',
    ];
}
