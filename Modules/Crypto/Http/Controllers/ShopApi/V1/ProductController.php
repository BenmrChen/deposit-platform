<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Illuminate\Routing\Controller;
use Modules\Crypto\Http\Requests\ShopApi\ProductListRequest;
use Modules\Crypto\Http\Resources\DepositApi\ProductListResource;
use Modules\Crypto\Services\ProductService;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    #[OA\Get(
        path: '/v1/products/{clientId}',
        tags: ['products'],
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
                description: 'product list.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/ProductListResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function getList(string $clientId)
    {
        $productList = $this->productService->getList($clientId);

        return new ProductListResource($productList);
    }
}
