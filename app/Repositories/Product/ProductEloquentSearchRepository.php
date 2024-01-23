<?php

namespace App\Repositories\Product;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductEloquentSearchRepository extends ProductSearchRepository
{
    public function search()
    {
        $collection = Product::query()
            ->when($this->titleQuery, function (Builder $query) {
                $query->where('title', 'like', "%{$this->titleQuery}%");
            })->when($this->maxPriceQuery, function (Builder $query) {
                $query->where('price', '<', $this->maxPriceQuery);
            })->when($this->sort, function (Builder $query) {
                $query->orderBy('price', 'asc');
            })->get();

        return ProductResource::collection($collection);
    }
}
