<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Cybavo;

use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Models\DepositOrder;
use Modules\Crypto\Tests\TestCase;
use Modules\Point\Tests\Helpers\CybavoGenerator;

class UpdateTest extends TestCase
{
    use CybavoGenerator;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiSecret = config('crypto.wallet.deposit.api_secret');
    }

    /**
     * Positive tests
     */
    public function testCybavoCallback()
    {
        $cybavoOrderId = 'cybavo_111-222-333_12345';
        $txId          = '0x0';
        $fromAddress   = '0x1';

        $order = DepositOrder::factory()->create([
            'cybavo_order_id' => $cybavoOrderId,
        ]);

        $routePath = route('shop-api.cybavo.confirmDeposit');
        $postData  = [
            'order_id'     => $cybavoOrderId,
            'state'        => 0,
            'txid'         => $txId,
            'from_address' => $fromAddress,
        ];

        $this->buildCheckSum($postData);

        $this->postJson($routePath, $postData)
            ->assertOk()
            ->assertSee('OK');

        $order->refresh();
        $this->assertEquals(DepositOrderStatus::PAID_SYSTEM->value, $order->status->value);
        $this->assertEquals($txId, $order->tx_id);
        $this->assertEquals($fromAddress, $order->from_address);

        $this->assertDatabaseHas('order_notifications', [
            'client_id' => $order->client_id,
            'order_id'  => $order->id,
        ]);
    }
}
