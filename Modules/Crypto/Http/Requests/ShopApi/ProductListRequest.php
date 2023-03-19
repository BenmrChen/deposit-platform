<?php

namespace Modules\Crypto\Http\Requests\ShopApi;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: 'ProductListRequest',
        required: [
            'clientId',
        ],
        properties: [
            new OA\Property(property: 'clientId', description: 'clientId', type: 'string', example: '9501abd7-9b59-4925-8a2a-bcb29cf3cd15', nullable: false),
        ],
        type: 'object'
    )
]
class ProductListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'clientId' => [
                'string',
            ],
        ];
    }
}
