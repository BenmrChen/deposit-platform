<?php

namespace Modules\Crypto\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Crypto\Database\factories\PaymentMethodFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $casts = [
        'info' => 'array',
    ];

    /**
     * @return PaymentMethodFactory
     */
    protected static function newFactory()
    {
        return PaymentMethodFactory::new();
    }
}
