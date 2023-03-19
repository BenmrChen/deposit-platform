<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Composer\DependencyResolver\Request;
use Illuminate\Routing\Controller;
use Modules\Crypto\Http\Resources\DepositApi\ClientListResource;
use Modules\Crypto\Http\Resources\DepositApi\ClientResource;
use Modules\Crypto\Services\ProductService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    #[OA\Get(
        path: '/v1/clients/{clientId}',
        tags: ['clients'],
        parameters: [
            new OA\Parameter(
                name: 'clientId',
                description: 'clientId',
                in: 'path',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'client detail.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/ClientResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function getDetail(string $clientId)
    {
        if (!$client = $this->productService->oauthClientRepo->findById($clientId)) {
            throw new NotFoundHttpException(404);
        }

        return new ClientResource($client);
    }

    #[OA\Get(
        path: '/v1/clients',
        tags: ['clients'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'client list.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/ClientListResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function getList()
    {
        $clientList = $this->productService->getClientList([
            'forDeposit' => true,
        ]);

        return new ClientListResource($clientList);
    }

    #[OA\Post(
        path: '/v1/clients:verify-account',
        tags: ['clients'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success.',
            ),
        ]
    )]
    public function verifyAccount(Request $request)
    {
        return [
            'data' => [
                'isExist' => true,
            ],
        ];
    }
}
