<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DbTransactions
{
    public function handle(Request $request, Closure $next): Response
    {
        DB::beginTransaction();

        $response = $next($request);

        if ($response->exception instanceof Throwable) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        return $response;
    }
}
