<?php

namespace Modules\Crypto\Tests\Feature\Command\Order;

use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Models\DepositOrder;
use Modules\Crypto\Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * Positive and negative tests: SET-CANCELLED
     */
    public function testModifyOrderStatus01()
    {
        // Negative test: status not correct
        $order = DepositOrder::factory()->create();

        $this->artisan("crypto:modify-order-status {$order->id} SET-CANCELLED")
            ->expectsOutput('Cannot set status cancelled as original status is not ERROR_DEPOSIT_EXCESS or ERROR_DEPOSIT_SHORTAGE')
            ->assertFailed();

        $order->refresh();
        $this->assertEquals(DepositOrderStatus::PENDING->value, $order->status->value);

        $order->status = DepositOrderStatus::ERROR_DEPOSIT_SHORTAGE;
        $order->save();

        // Positive test
        $this->artisan("crypto:modify-order-status {$order->id} SET-CANCELLED")
            ->expectsOutput('Successfully modified order status...')
            ->assertSuccessful();

        $order->refresh();
        $this->assertEquals(DepositOrderStatus::CANCELLED_MANUALLY->value, $order->status->value);
    }

    /**
     * Positive and negative tests: SET-PAID
     */
    public function testModifyOrderStatus02()
    {
        // Negative test: status not correct
        $order = DepositOrder::factory()->create();

        $this->artisan("crypto:modify-order-status {$order->id} SET-PAID")
            ->expectsOutput('Cannot set status paid as original status is not ERROR_DEPOSIT_EXCESS')
            ->assertFailed();

        $order->refresh();
        $this->assertEquals(DepositOrderStatus::PENDING, $order->status);

        $order->status = DepositOrderStatus::ERROR_DEPOSIT_EXCESS;
        $order->save();

        // Positive test
        $this->artisan("crypto:modify-order-status {$order->id} SET-PAID")
            ->expectsOutput('Successfully modified order status...')
            ->assertSuccessful();

        $order->refresh();
        $this->assertEquals(DepositOrderStatus::PAID_MANUALLY, $order->status);
    }
}
