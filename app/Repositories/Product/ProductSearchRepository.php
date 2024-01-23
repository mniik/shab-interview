<?php

namespace App\Repositories\Product;

abstract class ProductSearchRepository
{
    protected $sort;

    protected $maxPriceQuery;

    protected $titleQuery;

    abstract public function search();

    public function whenSortExists(?bool $condition): static
    {
        if ($condition) {
            $this->sort = 1;
        }

        return $this;
    }

    public function whenMaxPriceExists(?int $maxPriceQuery): static
    {
        if ($maxPriceQuery) {
            $this->maxPriceQuery = $maxPriceQuery;
        }

        return $this;
    }

    public function whenTitleExists(?string $titleQuery): static
    {
        if ($titleQuery) {
            $this->titleQuery = $titleQuery;
        }

        return $this;
    }
}
