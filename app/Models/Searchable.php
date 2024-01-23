<?php

namespace App\Models;

use App\Observers\ProductObserver;

trait Searchable
{
    public static function bootSearchable()
    {
        if (config('services.search.enabled')) {
            static::observe(ProductObserver::class);
        }
    }
}
