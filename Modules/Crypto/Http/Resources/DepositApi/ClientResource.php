<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: 'ClientResource',
        properties: [
            new OA\Property(property: 'clientId', description: '遊戲商id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'clientInfo', description: '遊戲商資訊', type: 'json', example: '{"zh_tw": {"name": "測試商戶", "description": "測試描述"}, "en_us": {"name": "test name", "description": "test description"}, "imageUrl": "https://testImage.com"}'),
        ],
        type: 'object'
    )
]
class ClientResource extends ResourceJsonBase
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    protected function getPayload($request): array
    {
        return [
            'clientId'   => $this->id,
            'clientInfo' => $this->client_info,
        ];
    }
}
