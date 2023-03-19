<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Illuminate\Routing\Controller;
use Modules\Crypto\Http\Resources\DepositApi\MetaResource;
use Modules\Crypto\Services\PaymentMethodService;
use OpenApi\Attributes as OA;

class MetaController extends Controller
{
    private $paymentMethodService;

    public function __construct(
        PaymentMethodService $paymentMethodService,
    ) {
        $this->paymentMethodService = $paymentMethodService;
    }
    #[OA\Get(
        path: '/v1/meta',
        summary: 'get deposit meta',
        tags: ['meta'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'meta info',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/ShopApi.MetaResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ],
    )]
    public function getMeta()
    {
        $list = $this->paymentMethodService->getList([
            'is_enabled' => true,
        ]);

        return new MetaResource($list);
    }
}
