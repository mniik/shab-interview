<?php

namespace App\Services\Product;

use App\Exceptions\FileUploadException;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(private readonly ProductRepository $productRepository)
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
}
