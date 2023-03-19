<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use App\Exceptions\AppException;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crypto\Http\Requests\ShopApi\CreateOrderRequest;
use Modules\Crypto\Http\Requests\ShopApi\CybavoDepositConfirmRequest;
use Modules\Crypto\Http\Requests\ShopApi\GetOrderListRequest;
use Modules\Crypto\Http\Resources\DepositApi\DepositOrderListResource;
use Modules\Crypto\Http\Resources\DepositApi\DepositOrderResource;
use Modules\Crypto\Services\CybavoService;
use Modules\Crypto\Services\OrderService;
use Modules\Crypto\Services\ProductService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    private $orderService;
    private $productService;
    private $cybavoService;

    public function __construct(
        OrderService $orderService,
        ProductService $productService,
        CybavoService $cybavoService,
    ) {
        $this->orderService   = $orderService;
        $this->productService = $productService;
        $this->cybavoService  = $cybavoService;
    }

    #[OA\Get(
        path: '/v1/orders',
        tags: ['orders'],
        parameters: [
            new OA\Parameter(
                name: 'clientId',
                description: 'clientId',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    example: '12345'
                )
            ),
            new OA\Parameter(
                name: 'statuses',
                description: 'statuses多選',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        enum: [
                            'PENDING',
                            'ITEM_RECEIVED',
                            'PAID_SYSTEM',
                            'PAID_MANUALLY',
                            'CALLING_BACK',
                            'CANCELLED_SYSTEM',
                            'CANCELLED_MANUALLY',
                            'EXPIRED',
                            'ERROR_DEPOSIT_SHORTAGE',
                            'ERROR_DEPOSIT_EXCESS',
                            'CALLBACK_FAIL',
                        ]
                    )
                ),
                style: 'form',
                explode: false,
            ),
            new OA\Parameter(
                name: 'chainId',
                description: 'chainId',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    example: '56'
                )
            ),
            new OA\Parameter(
                name: 'paymentType',
                description: 'paymentType',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    example: 'CRYPTO'
                )
            ),
            new OA\Parameter(
                name: 'sort',
                description: '-create-time,create-time',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    example: '-create-time'
                )
            ),
            new OA\Parameter(
                ref: '#/components/parameters/page'
            ),
            new OA\Parameter(
                ref: '#/components/parameters/pageSize'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'deposit order info.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/DepositOrderListResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function getList(GetOrderListRequest $request)
    {
        $userId = auth()->user()->uuid;
        $params = $request->validated();

        $orderList = $this->orderService->getList($userId, $params, ['client']);

        return new DepositOrderListResource($orderList);
    }

    #[OA\Get(
        path: '/v1/orders/{orderId}',
        tags: ['orders'],
        parameters: [
            new OA\Parameter(
                name: 'orderId',
                description: 'orderId',
                in: 'path',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    example: '12345'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'order detail.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/DepositOrderResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function getDetail(int $orderId)
    {
        $userId = auth()->user()->uuid;

        if (!$order = $this->orderService->findById($orderId)) {
            throw new NotFoundHttpException(404);
        }

        if ($order->user_id != $userId) {
            throw new NotFoundHttpException(404);
        }

        return new DepositOrderResource($order);
    }

    #[OA\Post(
        path: '/v1/orders',
        summary: 'create order',
        security: [['session' => []]],
        requestBody: new OA\RequestBody(
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/CreateOrderRequest'),
                ),
            ]
        ),
        tags: ['orders'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'order created info.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/DepositOrderResource',
                                type: 'object',
                            ),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function postCreate(CreateOrderRequest $request)
    {
        $input       = $request->validated();
        $userId      = auth()->user()->uuid;
        $gameUserId  = $input['gameUserId'];
        $productId   = $input['productId'];
        $paymentType = $input['paymentType'];
        $chainId     = $input['chainId'];

        $duration  = config('crypto.order_duration');
        $expiredAt = Carbon::now()->addMinutes($duration);

        $product = $this->productService->findById($productId);

        if (!$product) {
            throw new AppException(422, 'depositProductNotFound');
        }

        $clientId    = $product->client_id;
        $productCode = $product->product_code;
        $price       = (string) $product->price;

        try {
            DB::beginTransaction();

            $order = $this->orderService->create(
                $clientId,
                $userId,
                $gameUserId,
                $chainId,
                $productCode,
                $paymentType,
                $price,
                $expiredAt,
            );

            $cybavoOrderId = config('crypto.wallet.deposit.order_prefix') . "_{$product->client_id}_{$order->id}";

            $response = $this->cybavoService->createDepositOrder($cybavoOrderId, $product->price, $chainId);

            if ($response['status'] != 200) {
                throw new AppException(422, $response['result']);
            }

            $result = json_decode($response['result'], true);

            $order->to_address      = $result['address'];
            $order->cybavo_order_id = $cybavoOrderId;
            $order->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            throw new \Exception($th->getMessage());
        }

        try {
            DB::beginTransaction();

            $payload = [
                'orderId'     => $order->id,
                'productCode' => $order->product_code,
                'gameUserId'  => $order->game_user_id,
                'status'      => $order->status_text,
                'createTime'  => $order->created_at,
                'updateTime'  => $order->updated_at,
            ];
            $this->orderService->notificationRepo->create($order->client_id, $order->id, $payload);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            throw new \Exception($th->getMessage());
        }

        return new DepositOrderResource($order);
    }

    #[OA\Post(
        path: '/v1/orders/callbacks',
        tags: ['orders'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success.',
            ),
        ]
    )]
    public function postConfirmDeposit(CybavoDepositConfirmRequest $request)
    {
        $params = $request->validated();

        $this->cybavoService->confirmDeposit($params);

        return 'OK';
    }
}
