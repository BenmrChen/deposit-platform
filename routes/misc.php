<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('health-check', 'HealthCheckController', ['only' => ['index']]);
