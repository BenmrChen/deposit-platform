<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class GeneratePointApiSecret
{
    public function created(Model $model): void
    {
        /**
         * @var mixed $model
         */
        $model->point_api_secret = Crypt::encryptString(Str::random(48));
        $model->save();
    }
}
