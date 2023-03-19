<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Meta;

use Modules\Crypto\Models\PaymentMethod;
use Modules\Crypto\Tests\TestCase;

class ReadTest extends TestCase
{
    /**
     * Positive tests
     */
    public function testGetMeta()
    {
        PaymentMethod::factory()->create();
        PaymentMethod::factory()->create([
            'symbol' => 'USDT',
            'info'   => [
                'currency'  => 195,
                'address'   => '0x1',
                'chainId'   => '57',
                'chainName' => 'bsc',
            ],
        ]);
        PaymentMethod::factory()->create([
            'symbol' => 'TWD',
            'name'   => '3RD_PARTY',
            'info'   => [],
        ]);


        $routePath = route('shop-api.getMeta');

        $this->getJson($routePath)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'CRYPTO' => [
                        'USDT' => [
                            '*' => [
                                'chainId',
                                'chainName',
                            ],
                        ],
                    ],
                    '3RD_PARTY' => [
                        'TWD' => [
                            '*' =>  [
                                'chainId',
                                'chainName',
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
