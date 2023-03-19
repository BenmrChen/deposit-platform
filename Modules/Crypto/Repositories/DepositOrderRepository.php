<?php

namespace Modules\Crypto\Repositories;

use Illuminate\Support\Arr;
use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Models\DepositOrder;

class DepositOrderRepository extends AbstractRepository
{
    public function __construct(DepositOrder $depositOrder)
    {
        parent::__construct($depositOrder);
    }

    public function create(
        string $clientId,
        string $userId,
        string $gameUserId,
        string $chainId,
        string $productCode,
        string $paymentType,
        string $price,
        string $expiredAt,
    ) {
        return $this->model::create([
            'client_id'    => $clientId,
            'user_id'      => $userId,
            'game_user_id' => $gameUserId,
            'chain_id'     => $chainId,
            'product_code' => $productCode,
            'payment_type' => $paymentType,
            'price'        => $price,
            'expired_at'   => $expiredAt,
            'status'       => DepositOrderStatus::PENDING->value,
        ]);
    }

    public function getList(
        string $userId,
        array $input,
        array $with = [],
    ) {
        $query = $this->model::where('user_id', $userId);

        if ($clientId = Arr::get($input, 'clientId')) {
            $query->where('client_id', $clientId);
        }

        if ($statuses = Arr::get($input, 'statuses')) {
            $query->whereIn('status', DepositOrderStatus::fromArrayType($statuses));
        }

        if ($sort = Arr::get($input, 'sort')) {
            $sorting = $this->getSorting($sort);
            $query->orderBy($sorting['column'], $sorting['order']);
        } else {
            $query->orderBy('created_at', Arr::get($input, 'order', 'desc'));
        }

        if ($with) {
            $query->with($with);
        }

        return $query->paginate(
            Arr::get($input, 'pageSize', 20),
            ['*'],
            'page',
            Arr::get($input, 'page', 1)
        );
    }

    public function findByCybavoOrderId(
        string $cybavoOrderId
    ) {
        return $this->model::where('cybavo_order_id', $cybavoOrderId)->first();
    }
}
