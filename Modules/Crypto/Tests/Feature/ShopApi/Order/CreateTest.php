<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Order;

use Illuminate\Support\Facades\Auth;
use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Models\DepositProduct;
use Modules\Crypto\Models\PaymentMethod;
use Modules\Crypto\Repositories\DepositOrderRepository;
use Modules\Crypto\Repositories\PaymentMethodRepository;
use Modules\Crypto\Services\CybavoService;
use Modules\Passport\Models\User;
use Modules\Point\Enums\OrderNotificationStatus;
use Modules\Crypto\Tests\TestCase;
use Modules\Point\Repositories\OrderNotificationRepository;

class CreateTest extends TestCase
{
    private $cybavoServiceMock;

    /**
     * Negative tests
     */
    public function testCreateOrder01()
    {
        $user = User::first();
        Auth::login($user);

        PaymentMethod::factory()->create();

        $productId   = '12345';
        $gameUserId  = '23456';
        $paymentType = 'CRYPTO';
        $chainId     = '56';
        $toAddress   = 'TFeJ4jbSWRdRZWoxVrWnLSpGiQPWdJ2df';

        // mock CybavoService method
        $this->cybavoServiceMock = \Mockery::mock(
            CybavoService::class,
            [
                $this->app->make(DepositOrderRepository::class),
                $this->app->make(PaymentMethodRepository::class),
                $this->app->make(OrderNotificationRepository::class),
            ],
        )->makePartial();
        $this->instance(CybavoService::class, $this->cybavoServiceMock);

        $mockResponse = [
            'status' => 20003,
            'result' => json_encode([
                'error' => 'Wallet address not available',
            ]),
        ];
        $this->cybavoServiceMock->shouldReceive('createDepositOrder')->andReturn($mockResponse);

        $routePath = route('shop-api.orders.create');
        $postData  = [
            'gameUserId'  => $gameUserId,
            'productId'   => $productId,
            'paymentType' => $paymentType,
            'chainId'     => $chainId,
        ];

        $this->postJson($routePath, $postData)
            ->assertStatus(422)
            ->assertSee('depositProductNotFound');

        $product = DepositProduct::factory()->create([
            'id' => $productId,
        ]);

        $this->postJson($routePath, $postData)
            ->assertStatus(500)
            ->assertSee('Wallet address not available');

        $this->assertDatabaseMissing('deposit_orders', [
            'client_id'    => $product->client_id,
            'user_id'      => $user->uuid,
            'game_user_id' => $gameUserId,
            'chain_id'     => $chainId,
            'product_code' => $product->product_code,
            'payment_type' => $paymentType,
            'price'        => $product->price,
            'to_address'   => $toAddress,
            'status'       => DepositOrderStatus::PENDING->value,
        ]);
    }

    /**
     * Positive tests
     */
    public function testCreateOrder02()
    {
        $user = User::first();
        Auth::login($user);

        PaymentMethod::factory()->create();

        $productId   = '12345';
        $gameUserId  = '23456';
        $paymentType = 'CRYPTO';
        $chainId     = '56';
        $toAddress   = 'TFeJ4jbSWRdRZWoxVrWnLSpGiQPWdJ2df';

        $product = DepositProduct::factory()->create([
            'id' => $productId,
        ]);

        // mock CybavoService method
        $this->cybavoServiceMock = \Mockery::mock(
            CybavoService::class,
            [
                $this->app->make(DepositOrderRepository::class),
                $this->app->make(PaymentMethodRepository::class),
                $this->app->make(OrderNotificationRepository::class),
            ],
        )->makePartial();
        $this->instance(CybavoService::class, $this->cybavoServiceMock);

        $mockResponse = [
            'status' => 200,
            'result' => json_encode([
                'access_token' => 'ybJdKM_CT1yXxzLO2z1Y5fg1EzHuMyRA14ubzR8i-RE',
                'address'      => $toAddress,
                'expired_time' => 1615975467,
                'order_id'     => 'N520335069_1000022',
            ]),
        ];
        $this->cybavoServiceMock->shouldReceive('createDepositOrder')->andReturn($mockResponse);

        $routePath = route('shop-api.orders.create');
        $postData  = [
            'gameUserId'  => $gameUserId,
            'productId'   => $productId,
            'paymentType' => $paymentType,
            'chainId'     => $chainId,
        ];

        $response = $this->postJson($routePath, $postData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
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
                ],
            ]);

        $this->assertDatabaseHas('deposit_orders', [
            'client_id'    => $product->client_id,
            'user_id'      => $user->uuid,
            'game_user_id' => $gameUserId,
            'chain_id'     => $chainId,
            'product_code' => $product->product_code,
            'payment_type' => $paymentType,
            'price'        => $product->price,
            'to_address'   => $toAddress,
            'status'       => DepositOrderStatus::PENDING->value,
        ]);

        $responseData = $response->json('data');

        $this->assertDatabaseHas('order_notifications', [
            'status'   => OrderNotificationStatus::PENDING,
            'retries'  => 0,
            'order_id' => $responseData['orderId'],
            'client_id' => $responseData['clientId'],
        ]);
    }
}
