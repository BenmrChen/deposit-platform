<?php

namespace Modules\Crypto\Models;

use App\Traits\SnowflakeIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Crypto\Database\factories\DepositOrderFactory;
use Modules\Crypto\Enums\DepositOrderStatus;

class DepositOrder extends Model
{
    use HasFactory, SnowflakeIdTrait;

    public $incrementing = false;

    protected $fillable = [
        'client_id',
        'user_id',
        'game_user_id',
        'chain_id',
        'product_code',
        'payment_type',
        'price',
        'status',
        'expired_at',
        'cybavo_order_id',
    ];

    protected $casts = [
        'id'           => 'string',
        'status'       => DepositOrderStatus::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     *
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return DepositOrderStatus::name($this->status->value);
    }

    /**
     * @return DepositOrderFactory
     */
    protected static function newFactory(): DepositOrderFactory
    {
        return DepositOrderFactory::new();
    }
}
