<?php

namespace Modules\Crypto\Enums;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

enum DepositOrderStatus: int
{
    use EnumHelper;

    case PENDING                = 0;
    case ITEM_RECEIVED          = 1;
    case PAID_SYSTEM            = 2;
    case PAID_MANUALLY          = 3;
    case CALLING_BACK           = 4;
    case CANCELLED_SYSTEM       = 10;
    case CANCELLED_MANUALLY     = 11;
    case EXPIRED                = 12;
    case ERROR_DEPOSIT_SHORTAGE = 13;
    case ERROR_DEPOSIT_EXCESS   = 14;
    case CALLBACK_FAIL          = 15;

    /**
     * 依 Type 尋找符合的資料
     *
     * @param string $type
     * @return DepositOrderStatus
     */
    public static function fromType(string $type): DepositOrderStatus
    {
        return Arr::first(Arr::where(self::cases(), function ($case) use ($type) {
            return Str::contains($case->name, strtoupper($type));
        }));
    }
}
