<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductAddMediaRequest;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = $this->productService->store($request->validated());

        return $this->successResponse(data: $product, code: Response::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return $this->successResponse();
    }

    public function addMedia(ProductAddMediaRequest $request, Product $product): JsonResponse
    {
        $this->productService->addMedia(files: ['attachments'], product: $product);

        return $this->successResponse(code: Response::HTTP_CREATED);
    }
}
