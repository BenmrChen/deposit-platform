<?php

namespace Modules\Crypto\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Crypto\Models\DepositProduct;

class DepositProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DepositProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'client_id'    => Client::first()->id,
            'product_info' => [
                'en'       => 'testProduct',
                'cn'       => '測試商品',
                'imageUrl' => 'https://test.com',
            ],
            'product_code' => 'testCode',
            'payment_type' => 'CRYPTO',
            'price'        =>  1,
            'started_at'   => Carbon::now()->subMinutes(5),
            'ended_at'     => Carbon::now()->addMinutes(5),
        ];
    }
}
