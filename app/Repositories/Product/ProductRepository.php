<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Repository;

class ProductRepository extends Repository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }
}
