<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \SevenSenses\Routers\Middleware\SecureHeaders::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web'            => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'logger',
        ],
        'point-api'      => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            'auth',
            'logger',
            'forceJson',
        ],
        'point-exchange' => [
            'verify-client-signature',
            'logger',
            'forceJson',
        ],
        'point-vault'    => [
            'logger',
            'forceJson',
        ],
        'shop-api'    => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            'logger',
            'forceJson',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                     => \App\Http\Middleware\Authenticate::class,
        'auth.basic'               => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers'            => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                      => \Illuminate\Auth\Middleware\Authorize::class,
        'password.confirm'         => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'                   => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'                 => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'                 => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'forceJson'                => \App\Http\Middleware\ForceJson::class,
        'db.transaction'           => \App\Http\Middleware\DbTransactions::class,
        'logger'                   => \SevenSenses\ApiLogger\RequestLogger::class,
        'verify-client-signature'  => \Modules\Point\Http\Middleware\VerifyClientSignature::class,
        'verify-client-withdrawal' => \Modules\Point\Http\Middleware\VerifyClientWithdrawal::class,
        'user-binding'             => \Modules\Point\Http\Middleware\UserBinding::class,
        'verify-cybavo'            => \Modules\Point\Http\Middleware\VerifyCybavoChecksum::class,
        'verify-cybavo-deposit'    => \Modules\Crypto\Http\Middleware\VerifyCybavoDepositChecksum::class,
    ];

    protected $middlewarePriority = [
        \App\Http\Middleware\ForceJson::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \SevenSenses\ApiLogger\RequestLogger::class,
        \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
