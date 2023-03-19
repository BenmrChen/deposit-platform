<?php

namespace Modules\Crypto\Http\Requests\ShopApi;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

class AuthUrlRequest extends FormRequest
{
    #[
        OA\Schema(
            schema: 'AuthUrlRequest',
            required: [
                'redirectUrl',
                'state',
            ],
            properties: [
                new OA\Property(property: 'redirectUrl', description: 'redirectUrl', type: 'string', example: 'https://localhost:3000/account/oauth', nullable: false),
                new OA\Property(property: 'state', description: 'state', type: 'string', example: 'test', nullable: true),
            ],
            type: 'object'
        )
    ]
    public function rules()
    {
        $rules = [
            'redirectUrl' => ['required', 'url', 'regex:/^(http|https).+$/'],
            'state'       => 'nullable|string',
        ];

        return $rules;
    }
}
