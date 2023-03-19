<?php

namespace Modules\Crypto\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Models\DepositOrder;


class DepositOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DepositOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'client_id'    => Client::first()->id,
            'user_id'      => User::first()->uuid,
            'game_user_id' => $this->faker->uuid,
            'chain_id'     => '57',
            'product_code' => 'test',
            'payment_type' => 'CRYPTO',
            'price'        => 1,
            'status'       => DepositOrderStatus::PENDING->value,
            'expired_at'   => Carbon::now()->addMinutes(5),
        ];
    }
}
