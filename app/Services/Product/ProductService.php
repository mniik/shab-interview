<?php

namespace App\Services\Product;

use App\Exceptions\FileUploadException;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductSearchRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private ProductSearchRepository $searchRepository)
    {
    }

    public function store(array $request): Model
    {
        return $this->productRepository->create([...$request, 'user_id' => auth()->id()]);
    }

    public function delete(Product $product): ?bool
    {
        return $this->productRepository->delete($product);
    }

    public function addMedia(array $files, Product $product)
    {
        try {
            $product->addMultipleMediaFromRequest($files)
                ->each(function ($fileAdder) {
                    $fileAdder->setFileName(time().'-'.Str::random(10))
                        ->toMediaCollection('images');
                });

        } catch (\Throwable $e) {
            throw new FileUploadException();
        }

    }

    public function search($request)
    {
        return $this->searchRepository->whenTitleExists($request->input('title'))
            ->whenMaxPriceExists($request->input('maxPrice'))
            ->whenSortExists($request->input('sortBy'))
            ->search();
    }
}
