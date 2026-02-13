<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUlidColumn
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootHasUlidColumn()
    {
        static::creating(function ($model) {
            if (is_null($model->getRouteKey())) {
                $model->{$model->getRouteKeyName()} = Str::ulid();
            }
        });
    }

    public function initializeHasUlidColumn()
    {
        if (! in_array('id', $this->hidden)) {
            $this->hidden[] = 'id';
        }
    }

    public function getRouteKeyName()
    {
        return $this->getUlidColumn();
    }

    public function getUlidColumn()
    {
        return defined(static::class.'::ULID_COLUMN') ? static::ULID_COLUMN : 'ulid';
    }
}
