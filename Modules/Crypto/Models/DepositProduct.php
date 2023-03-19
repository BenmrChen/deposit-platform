<?php

namespace Modules\Crypto\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Crypto\Database\factories\DepositProductFactory;

class DepositProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_code',
        'payment_type',
        'price',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'product_info' => 'array',
    ];

    /**
     * @return DepositProductFactory
     */
    protected static function newFactory(): DepositProductFactory
    {
        return DepositProductFactory::new();
    }
}
