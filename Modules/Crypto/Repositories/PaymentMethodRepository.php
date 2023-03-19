<?php

namespace Modules\Crypto\Repositories;

use Illuminate\Support\Arr;
use Modules\Crypto\Models\PaymentMethod;

class PaymentMethodRepository extends AbstractRepository
{
    public function __construct(PaymentMethod $depositOrder)
    {
        parent::__construct($depositOrder);
    }

    public function getList(
        array $input,
    ) {
        $query = $this->model;

        if ($isEnabled = Arr::get($input, 'isEnabled')) {
            $query->where('is_enabled', $isEnabled);
        }

        return $query->all();
    }

    public function findByCryptoChainId(
        string $chainId
    ) {
        return $this->model->where([
            'name'          => 'CRYPTO',
            'symbol'        => 'USDT',
            'info->chainId' => $chainId
        ])
            ->first();
    }
}
