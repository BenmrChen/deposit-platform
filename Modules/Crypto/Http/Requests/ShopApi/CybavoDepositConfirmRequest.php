<?php

namespace Modules\Crypto\Http\Requests\ShopApi;

use Illuminate\Foundation\Http\FormRequest;

class CybavoDepositConfirmRequest extends FormRequest
{
    public function rules()
    {
        return [
            'order_id'     => 'required|string',
            'state'        => 'required|integer',
            'txid'         => 'required|string',
            'from_address' => 'required|string',
        ];
    }
}
