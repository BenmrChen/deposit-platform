<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AuthUrlResource extends ResourceJsonBase
{
    #[OA\Schema(
        schema: 'AuthUrlResource',
        properties: [
            new OA\Property(property: 'url', description: 'url', type: 'string', example: 'localhost:3000'),
            new OA\Property(property: 'state', description: 'state', type: 'string', example: '12345'),
        ],
        type: 'object',
    )]
    protected function getPayload(Request $request): array
    {
        return [
            'passportAuthUrl' => $this->passportAuthUrl,
            'state'           => $this->state,
        ];
    }
}
