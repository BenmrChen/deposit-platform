<?php

namespace Modules\Crypto\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Crypto\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'     => 'CRYPTO',
            'symbol'   => 'USDT',
            'info'     => [
                'currency'     => 195,
                'tokenAddress' => 'TUV1qK5G9szesx4Fa9BDQwwnHAxGTdRTci',
                'chainId'      => '56',
                'chainName'    => 'tron',
            ],
            'is_enabled' => true,
        ];
    }
}
