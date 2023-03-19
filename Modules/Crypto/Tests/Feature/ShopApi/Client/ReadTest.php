<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Client;

use Modules\Crypto\Tests\TestCase;
use Modules\Passport\Models\Client;

class ReadTest extends TestCase
{
    /**
     * Positive tests
     */
    public function testGetDetail()
    {
        $clientId  = Client::first()->id;
        $routePath = route('shop-api.clients.getDetail', ['clientId' => $clientId]);

        $this->getJson($routePath)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'clientId',
                    'clientInfo',
                ],
            ]);
    }

    /**
     * Positive tests
     */
    public function testGetList()
    {
        $client1                  = Client::first();
        $client1->deposit_enabled = true;
        $client1->save();

        // client2 would not be included in response as it is not enabled for deposit
        $client2 = Client::factory()->createOne([
            'id'               => '9501abd7-9b59-4925-8a2a-bcb29cf3cd16',
            'name'             => 'default',
            'secret'           => 'gl6L6mKWDQJXDt7hxs3Ct5Pq9VCQLwhTCjVRBq6b',
            'point_api_secret' => 'eyJpdiI6IjZ0WTk5K083VzlvK1dkUDFPYUhadXc9PSIsInZhbHVlIjoiZVV6YngvMG1lK3AzN0dGMmxoYUdqdE9xMWovYlRHR0p4STB6OVF4c0JtT1JGRVJkQkk1TDJXOHVYbDI2R0ZKTFNnTGh1YW05bGxpM1I2elcwNDVTM2c9PSIsIm1hYyI6IjEyNTllOTgzNDZmMDhiNGZjYWJjZDI2Nzk2MTUwN2FlODgzMGJmMDZjNGYyMWIzODdkYzg3YzRlMDk0ZWI4NWYiLCJ0YWciOiIifQ==',
            'redirect'         => 'http://localhost:3000/callback',
            'symbols'          => json_encode(['UCG']),
            'deposit_enabled'  => false,
        ]);

        $routePath = route('shop-api.clients.getList');

        $response = $this->getJson($routePath)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'clientId',
                        'clientInfo',
                    ],
                ],
            ]);
        $this->assertCount(1, $response->json('data'));
    }
}
