<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Illuminate\Routing\Controller;
use Modules\Crypto\Http\Requests\ShopApi\CybavoDepositConfirmRequest;
use Modules\Crypto\Services\CybavoService;
use OpenApi\Attributes as OA;

class CybavoController extends Controller
{
    private $cybavoService;

    public function __construct(
        CybavoService $cybavoService,
    ) {
        $this->cybavoService = $cybavoService;
    }

    #[OA\Post(
        path: '/v1/cybavo/deposit-confirm/callbacks',
        tags: ['cybavo'],
        responses: [
            new OA\Response(response: 200, description: 'OK message'),
        ],
    )]
    public function postConfirmDeposit(CybavoDepositConfirmRequest $request)
    {
        $params = $request->validated();

        $this->cybavoService->confirmDeposit($params);

        return 'OK';
    }
}
