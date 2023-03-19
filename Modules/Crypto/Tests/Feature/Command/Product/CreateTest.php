<?php

namespace Modules\Crypto\Tests\Feature\Command\Product;

use Modules\Point\Tests\TestCase;

class CreateTest extends TestCase
{
    /**
     * 新增 client allowlist ip
     *
     * @return void
     */
    public function testAddProduct()
    {
        $clientId    = '9501abd7-9b59-4925-8a2a-bcb29cf3cd15';
        $productCode = 'test-product';
        $paymentType = 'CRYPTO';
        $price       = '0.1';
        $startedAt   = '"2023-01-01 00:00:00"';
        $endedAt     = '"2023-12-31 23:59:59"';

        $this->artisan("crypto:add-product {$clientId} {$productCode} {$paymentType} $price {$startedAt} {$endedAt}")
            ->expectsOutput('Successfully added product...')
            ->assertSuccessful();

        $this->assertDatabaseHas('deposit_products', [
            'client_id'    => $clientId,
            'product_code' => $productCode,
            'payment_type' => $paymentType,
            'price'        => $price,
            'started_at'   => str_replace('"', '', $startedAt),
            'ended_at'     => str_replace('"', '', $endedAt),
        ]);
    }
}
