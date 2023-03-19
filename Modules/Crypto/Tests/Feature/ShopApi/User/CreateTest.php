<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\User;

use Illuminate\Support\Facades\Auth;
use Modules\Crypto\Tests\TestCase;
use Modules\Passport\Models\User;

class CreateTest extends TestCase
{
    /**
     * Positive tests
     */
    public function testGetAuthUrl()
    {
        $user = User::first();
        Auth::login($user);

        // 1. post with state input
        $postData  = [
            'redirectUrl' => 'https://localhost:3000/account/oauth',
            'state'       => 'test',
        ];
        $routePath = route('shop-api.getAuthUrl');

        $this->postJson($routePath, $postData)
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'passportAuthUrl',
                'state',
            ],
        ]);

        // 2. post without state input
        $postData  = [
            'redirectUrl' => 'https://localhost:3000/account/oauth',
        ];
        $routePath = route('shop-api.getAuthUrl');

        $this->postJson($routePath, $postData)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'passportAuthUrl',
                    'state',
                ],
            ]);
    }
}
