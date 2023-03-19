<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: '0.0.1',
        description: 'crypto api swagger document',
        title: 'crypto api swagger document'
    ),
    OA\Tag(
        name: 'users',
        description: 'users api'
    ),
    OA\Tag(
        name: 'meta',
        description: 'meta api'
    ),
    OA\Tag(
        name: 'clients',
        description: 'client api'
    ),
    OA\Tag(
        name: 'orders',
        description: 'order api'
    ),
    OA\Tag(
        name: 'products',
        description: 'product api'
    ),
    OA\Tag(
        name: 'cybavo',
        description: 'Receive callbacks from cybavo.'
    ),
    OA\SecurityScheme(
        securityScheme: 'ApiKeyAuth',
        type: 'apiKey',
        name: 'X-SIGNATURE',
        in: 'header'
    ),
    OA\Parameter(
        name: 'X-CLIENT-ID',
        description: 'client id',
        in: 'header',
        schema: new OA\Schema(type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15')
    ),
    OA\Parameter(
        name: 'X-TIMESTAMP',
        description: 'current timestamp (seconds)',
        in: 'header',
        schema: new OA\Schema(type: 'integer', minimum: 1, example: '1603180032')
    ),
    OA\Parameter(
        name: 'X-SIGNATURE',
        description: 'sha256 hashed signature',
        in: 'header',
        schema: new OA\Schema(type: 'string', example: 'c142b1990b42ce9a5088c6c9dafafbcb8074a5673e5e899196b4d9bfd2676f60')
    ),
    OA\Parameter(
        name: 'X-BYPASS-SIGNATURE',
        description: '[TEST ENV] enable to skip signature validation',
        in: 'header',
        schema: new OA\Schema(type: 'boolean', example: false)
    ),
    OA\Parameter(
        name: 'userId',
        description: 'user id',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: '95f14356-6b77-4764-871f-58a09013c91b')
    ),
    OA\Parameter(
        name: 'clientId',
        description: 'client uuid',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    ),
    OA\Parameter(
        name: 'page',
        description: 'switch current page of return list.',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            default: 1,
            minimum: 1
        )
    ),
    OA\Parameter(
        name: 'pageSize',
        description: 'how much items in per page',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            default: 20,
            minimum: 1
        )
    ),
]
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
