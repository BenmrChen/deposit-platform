<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: 'ProductListResource',
        properties: [
            new OA\Property(property: 'productId', description: '商品id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'productInfo', description: '商品info', type: 'json', example: '{"zh_tw": "測試商品", "en_us": "testProduct", "imageUrl": "https://test.com"}'),
            new OA\Property(property: 'productCode', description: '商品code', type: 'string', example: 'test-item'),
            new OA\Property(property: 'paymentType', description: '付款方式', type: 'string', example: 'CRYPTO'),
            new OA\Property(property: 'price', description: '價格', type: 'string', example: '10'),
        ],
        type: 'object'
    )
]
class ProductListResource extends ResourceJsonBase
{
    public function __construct($resource)
    {
        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    public function getPayload($request): array
    {
        return [
            'data' => $this->resource->map(function ($item) {
                return [
                    'productId'   => $item->id,
                    'productInfo' => $item->product_info,
                    'productCode' => $item->product_code,
                    'paymentType' => $item->payment_type,
                    'price'       => $item->price,
                    'symbol'      => 'USDT',
                ];
            }),
        ];
    }
}
