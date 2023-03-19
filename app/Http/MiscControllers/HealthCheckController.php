<?php

namespace App\Http\MiscControllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class HealthCheckController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['status' => 'ok'], 200);
    }
}
