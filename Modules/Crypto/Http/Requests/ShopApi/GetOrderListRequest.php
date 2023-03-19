<?php

namespace Modules\Crypto\Http\Requests\ShopApi;

use Illuminate\Foundation\Http\FormRequest;

class GetOrderListRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clientId' => [
                'string',
            ],
            'sort'     => [
                'nullable',
                'string',
                'in:-create-time,create-time',
            ],
            'page'     => 'nullable|integer|min:1',
            'pageSize' => 'nullable|integer|min:1|max:100',
        ];
    }
}
