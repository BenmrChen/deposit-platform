<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: 'DepositOrderResource',
        properties: [
            new OA\Property(property: 'orderId', description: '訂單id', type: 'string', example: '1754888844642369537'),
            new OA\Property(property: 'clientId', description: '遊戲商id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'userId', description: '用戶id', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15'),
            new OA\Property(property: 'gameUserId', description: '遊戲用戶id', type: 'string', example: '4642369537'),
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
    )
]
class DepositOrderResource extends ResourceJsonBase
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
            'orderId'     => $this->id,
            'clientId'    => $this->client_id,
            'userId'      => $this->user_id,
            'gameUserId'  => $this->game_user_id,
            'chainId'     => $this->chain_id,
            'productId'   => $this->product_id,
            'productCode' => $this->product_code,
            'paymentType' => $this->payment_type,
            'price'       => $this->price,
            'toAddress'   => $this->to_address,
            'fromAddress' => $this->from_address,
            'expiredAt'   => $this->expired_at,
            'txId'        => $this->tx_id,
            'status'      => $this->status_text,
            'createTime'  => $this->created_at,
            'updateTime'  => $this->updated_at,
        ];
    }
}
