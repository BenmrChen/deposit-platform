<?php

namespace Modules\Crypto\Http\Requests\ShopApi;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: 'CreateOrderRequest',
        required: [
            'gameUserId',
            'productId',
            'paymentType',
            'chainId',
        ],
        properties: [
            new OA\Property(property: 'gameUserId', description: '遊戲id', type: 'string', example: '12345', nullable: false),
            new OA\Property(property: 'productId', description: '商品id', type: 'string', example: '12345', nullable: false),
            new OA\Property(property: 'paymentType', description: '付款方式', type: 'string', enum: ['CRYPTO', '3RD_PARTY'], example: 'CRYPTO', nullable: false),
            new OA\Property(property: 'chainId', description: '鏈id', type: 'string', example: '56', nullable: false),
        ],
        type: 'object'
    )
]
class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gameUserId'  => [
                'required',
            ],
            'productId'   => [
                'required',
            ],
            'paymentType' => [
                'required',
            ],
            'chainId'     => [
                'required',
            ],
        ];
    }
}
