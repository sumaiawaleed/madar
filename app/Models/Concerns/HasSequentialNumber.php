<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasSequentialNumber
{
    /**
     * Give each new record the next number in its owner's own sequence (1, 2, 3…).
     */
    public static function bootHasSequentialNumber(): void
    {
        static::creating(function (Model $model): void {
            if ($model->number === null) {
                $model->number = (int) static::query()->where('user_id', $model->user_id)->max('number') + 1;
            }
        });
    }
}
