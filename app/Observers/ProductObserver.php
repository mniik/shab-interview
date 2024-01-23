<?php

namespace App\Observers;

use App\Jobs\DeleteProductElasticJob;
use App\Jobs\UpdateProductElasticJob;
use App\Models\Product;

class ProductObserver
{
    public function created(Product $product): void
    {
        UpdateProductElasticJob::dispatch($product);
    }

    public function deleted(Product $product): void
    {
        DeleteProductElasticJob::dispatch($product);
    }
}
