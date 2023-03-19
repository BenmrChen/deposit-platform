<?php

use Illuminate\Support\Facades\Route;

Route::as('shop-api.')->group(function () {
    Route::get('meta', 'MetaController@getMeta')->name('getMeta');

    Route::post('users:get-auth-url', 'UserController@postGetAuthUrl')->name('getAuthUrl');

    Route::middleware(['auth'])->group(function () {
        Route::apiResource('me', 'MeController', ['only' => ['index']]);
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('{clientId}', 'ProductController@getList')->name('getList');
    });

    Route::prefix('orders')->name('orders.')->middleware(['auth'])->group(function () {
        Route::get('', 'OrderController@getList')->name('getList');
        Route::get('{orderId}', 'OrderController@getDetail')->name('getDetail');
        Route::post('', 'OrderController@postCreate')->name('create');
        Route::post('callbacks', 'OrderController@postConfirmDeposit')->name('confirmDeposit');
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('{clientId}', 'ClientController@getDetail')->name('getDetail');
        Route::get('', 'ClientController@getList')->name('getList');
        Route::post('{clientId}:verify-account', 'ClientController@verifyAccount')->name('verify-account');
    });

    Route::prefix('cybavo')->name('cybavo.')->middleware(['verify-cybavo-deposit'])->group(function () {
        Route::post('deposit-confirm/callbacks', 'CybavoController@postConfirmDeposit')->name('confirmDeposit');
    });
});
