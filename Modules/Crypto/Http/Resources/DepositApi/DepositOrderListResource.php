<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use Illuminate\Http\Request;
use App\Http\Resources\ResourceJsonBase;
use OpenApi\Attributes as OA;

class DepositOrderListResource extends ResourceJsonBase
{
    private $pagination;

    public function __construct($resource)
    {
        $this->pagination = $this->getPagination($resource);
        $resource         = $resource->getCollection();

        parent::__construct($resource);
    }

    #[OA\Schema(
        schema: 'DepositOrderListResource',
        properties: [
            new OA\Property(property: 'orderId', description: '訂單id', type: 'string', example: '1754888844642369537'),
            new OA\Property(property: 'userId', description: '用戶id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'gameUserId', description: '遊戲用戶id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'chainId', description: '鏈id', type: 'string', example: '57'),
            new OA\Property(property: 'productId', description: '商品id', type: 'string', example: '1754888844642369537'),
            new OA\Property(property: 'productCode', description: '商品code', type: 'string', example: 'test-item'),
            new OA\Property(property: 'paymentType', description: '付款方式', type: 'string', example: 'CRYPTO'),
            new OA\Property(property: 'price', description: '價格', type: 'string', example: '10'),
            new OA\Property(property: 'toAddress', description: '收款地址', type: 'string', example: '0x1'),
            new OA\Property(property: 'fromAddress', description: '付款人地址', type: 'string', example: '0x2'),
            new OA\Property(property: 'expiredAt', description: '過期時間', type: 'string', example: '2023-06-24 06:46:31'),
            new OA\Property(property: 'txId', description: 'txId', type: 'string', example: '0x2'),
            new OA\Property(property: 'status', description: '狀態', type: 'string', example: 'PENDING'),
            new OA\Property(property: 'createTime', description: '創建時間', type: 'string', example: '2023-06-24 06:46:31'),
        ],
        type: 'object'
    )]
    protected function getPayload(Request $request): array
    {
        return array_merge(
            $this->pagination['meta'],
            [
                'data' => $this->resource->map(function ($item) {
                    return [
                        'orderId'     => $item->order_id,
                        'userId'      => $item->user_id,
                        'gameUserId'  => $item->game_user_id,
                        'productId'   => $item->product_id,
                        'productCode' => $item->product_code,
                        'chainId'     => $item->chain_id,
                        'price'       => $item->price,
                        'paymentType' => $item->payment_type,
                        'fromAddress' => $item->from_address,
                        'toAddress'   => $item->to_address,
                        'txId'        => $item->txid,
                        'expiredAt'   => $item->expired_at,
                        'status'      => $item->status_text,
                        'createTime'  => $item->created_at,
                    ];
                }),
            ]
        );
    }
}
