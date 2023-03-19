<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;

class MetaResource extends ResourceJsonBase
{
    #[OA\Schema(
        schema: 'ShopApi.MetaResource',
        properties: array(
            new Property(
                property: 'CRYPTO',
                description: '加密貨幣',
                properties: array (
                    new Property(
                        property: 'USDT',
                        description: 'USDT',
                        properties: [
                            new Property(property: 'chainId', description: '鏈id', type: 'string', example: "56"),
                            new Property(property: 'chainName', description: '鏈名', type: 'string', example: "bsc"),
                        ],
                    ),
                ),
                type: 'object',
            ),
            new Property(
                property: '3RD_PARTY',
                description: '第三方支付',
                properties: array (
                    new Property(
                        property: 'TWD',
                        description: 'TWD',
                        properties: [
                        ],
                    ),
                ),
                type: 'object',
            ),
        ),
    )]

    protected function getPayload(Request $request): array
    {
        return $this->resource->map(function ($item) {
            return [
                'paymentType'  => $item->name,
                'symbol'       => $item->symbol,
                'tokenAddress' => $item->info['tokenAddress'] ?? null,
                'currency'     => $item->info['currency'] ?? null,
                'chainId'      => $item->info['chainId'] ?? null,
                'chainName'    => $item->info['chainName'] ?? null,
            ];
        })->groupBy('paymentType')->map(function ($item) {
            return $item->groupBy('symbol');
            // remove useless columns which frontend doesn't need
        })->map(function ($crypto) {
            return $crypto->map(function ($currency) {
                return $currency->map(function ($item) {
                    return collect($item)->only(['chainId', 'chainName']);
                });
            });
        })->toArray();
    }
}
