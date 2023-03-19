<?php

namespace Modules\Crypto\Repositories;

use Illuminate\Support\Arr;
use Modules\Crypto\Models\DepositProduct;

class DepositProductRepository extends AbstractRepository
{
    public function __construct(DepositProduct $depositProduct)
    {
        parent::__construct($depositProduct);
    }

    public function create(
        string $clientId,
        string $productCode,
        string $paymentType,
        string $price,
        string $startedAt,
        string $endedAt,
    ) {
        return $this->model::create([
            'client_id'    => $clientId,
            'product_code' => $productCode,
            'payment_type' => $paymentType,
            'price'        => $price,
            'started_at'   => $startedAt,
            'ended_at'     => $endedAt,
        ]);
    }

    public function getList(
        string $clientId,
        array $input = null,
    ) {
        $query = $this->model::where('client_id', $clientId);

        if ($sort = Arr::get($input, 'sort')) {
            $sorting = $this->getSorting($sort);
            $query->orderBy($sorting['column'], $sorting['order']);
        } else {
            $query->orderBy('created_at', Arr::get($input, 'order', 'desc'));
        }

        return $query->paginate(
            Arr::get($input, 'pageSize', 20),
            ['*'],
            'page',
            Arr::get($input, 'page', 1)
        );
    }
}
