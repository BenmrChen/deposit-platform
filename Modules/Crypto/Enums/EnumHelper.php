<?php

namespace Modules\Crypto\Enums;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait EnumHelper
{
    public static function names(): array
    {
        return Arr::pluck(self::cases(), 'name');
    }

    public static function name($value)
    {
        return Arr::first(self::cases(), function ($case) use ($value) {
            return $case->value === $value;
        })?->name;
    }

    public static function fromType(string $type)
    {
        return Arr::where(self::cases(), function ($case) use ($type) {
            return Str::contains($case->name, strtoupper($type));
        });
    }

    public static function fromArrayType(array $types): array
    {
        $data = [];

        foreach ($types as $type) {
            $data[] = Arr::first(self::cases(), function ($case) use ($type) {
                return $case->name === $type;
            })->value;
        }

        return $data;
    }
}
