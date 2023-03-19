<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        $url = config('frontend.login_url');

        if ($request->fixedEmail) {
            $query = http_build_query([
                'fixedEmail' => $request->fixedEmail,
            ]);

            $url .= "?{$query}";
        }

        if (!$request->expectsJson()) {
            return $url;
        }
    }
}
