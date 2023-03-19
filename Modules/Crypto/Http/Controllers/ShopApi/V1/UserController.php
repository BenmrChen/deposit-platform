<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Illuminate\Routing\Controller;
use Modules\Crypto\Http\Requests\ShopApi\AuthUrlRequest;
use Modules\Crypto\Http\Resources\DepositApi\AuthUrlResource;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Post(
        path: '/v1/users:get-auth-url',
        requestBody: new OA\RequestBody(
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/AuthUrlRequest'),
                ),
            ]
        ),
        tags: ['users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'get auth url.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/AuthUrlResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function postGetAuthUrl(AuthUrlRequest $request)
    {
        $params      = $request->validated();
        $redirectUrl = $params['redirectUrl'];
        $state       = $params['state'] ?? null;

        // get url
        $queryParams = [
            'client_id'     => config('crypto.passport_client_id'),
            'redirect_uri'  => $redirectUrl,
            'response_type' => 'code',
            'scope'         => '',
            'state'         => $state,
        ];

        $url = config('crypto.passport_api_url') . '/v1/oauth/authorize?' . http_build_query($queryParams);

        return new AuthUrlResource([
            'passportAuthUrl' => $url,
            'state'           => $state,
        ]);
    }
}
