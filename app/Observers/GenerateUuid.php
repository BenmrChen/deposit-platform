<?php

namespace App\Observers;

use App\Models\ShouldHaveUuid;
use App\Models\ShouldUseUuidAsPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GenerateUuid
{
    public function creating(Model $model): void
    {
        $interfaces = class_implements($model);
        $interfaces = $interfaces ?: [];

        if (Arr::has($interfaces, ShouldHaveUuid::class)) {
            $model->setAttribute('uuid', Str::orderedUuid()->toString());
        }

        if (Arr::has($interfaces, ShouldUseUuidAsPrimaryKey::class)) {
            $model->setAttribute($model->getKeyName(), Str::orderedUuid()->toString());
        }
    }
}
