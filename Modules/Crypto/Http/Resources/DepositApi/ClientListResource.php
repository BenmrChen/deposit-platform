<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: 'ClientListResource',
        properties: [
            new OA\Property(property: 'clientId', description: '遊戲商id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'clientInfo', description: '遊戲商資訊', type: 'json', example: '{"cn": {"name": "測試商戶", "description": "測試描述"}, "en": {"name": "test name", "description": "test description"}, "imageUrl": "https://testImage.com"}'),
        ],
        type: 'object'
    )
]
class ClientListResource extends ResourceJsonBase
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
                    'clientId'   => $item->id,
                    'clientInfo' => $item->client_info,
                ];
            }),
        ];
    }
}
