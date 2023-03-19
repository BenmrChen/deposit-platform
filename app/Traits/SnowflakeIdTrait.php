<?php

namespace App\Traits;

use App\Helpers\IdHelper;

trait SnowflakeIdTrait
{
    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (new IdHelper())->genId();

            return true;
        });
    }
}
