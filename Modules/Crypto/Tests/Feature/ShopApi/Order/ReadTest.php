<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Order;

use Illuminate\Support\Facades\Auth;
use Modules\Crypto\Models\DepositOrder;
use Modules\Passport\Models\Client;
use Modules\Passport\Models\User;
use Modules\Point\Tests\TestCase;

class ReadTest extends TestCase
{
    /**
     * Positive tests
     */
    public function testGetList()
    {
        $user = User::first();
        Auth::login($user);

        $clientId = Client::first()->id;

        DepositOrder::factory()->create([
            'user_id'   => $user,
            'client_id' => $clientId,
        ]);

        DepositOrder::factory()->create([
            'user_id'   => $user,
            'client_id' => '12345',
        ]);

        DepositOrder::factory()->create([
            'user_id'   => '12345',
            'client_id' => $clientId,
        ]);

        $routePath = route('shop-api.orders.getList', ['clientId' => $clientId]);

        $response = $this->getJson($routePath)
            ->assertJsonStructure([
                'totalRows',
                'totalPages',
                'pageSize',
                'currentPage',
                'data' => [
                    '*' => [
                        'orderId',
                        'userId',
                        'gameUserId',
                        'chainId',
                        'productId',
                        'productCode',
                        'paymentType',
                        'price',
                        'toAddress',
                        'fromAddress',
                        'expiredAt',
                        'txId',
                        'status',
                        'createTime',
                    ],
                ],
            ]);
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Positive tests
     */
    public function testGetDetail()
    {
        $user = User::first();
        Auth::login($user);

        $order = DepositOrder::factory()->create([
            'user_id' => $user,
        ]);

        $routePath = route('shop-api.orders.getDetail', ['orderId' => $order->id]);

        $this->getJson($routePath)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'orderId',
                    'clientId',
                    'userId',
                    'gameUserId',
                    'chainId',
                    'productId',
                    'productCode',
                    'paymentType',
                    'price',
                    'toAddress',
                    'fromAddress',
                    'expiredAt',
                    'txId',
                    'status',
                    'createTime',
                ],
            ]);
    }
}
