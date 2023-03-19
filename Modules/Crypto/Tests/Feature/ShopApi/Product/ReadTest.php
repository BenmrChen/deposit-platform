<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Product;

use Modules\Crypto\Models\DepositProduct;
use Modules\Crypto\Tests\TestCase;
use Modules\Passport\Models\Client;

class ReadTest extends TestCase
{
    /**
     * Positive tests
     */
    public function testGetList()
    {
        DepositProduct::factory()->create();
        $routePath = route('shop-api.products.getList', ['clientId' => Client::first()->id]);

        $this->getJson($routePath)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' =>
                        [
                            'productId',
                            'productCode',
                            'paymentType',
                            'price',
                            'symbol',
                        ],
                ],
            ]);
    }
}
