<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function addToCart(Product $product): JsonResponse
    {
        $this->cartService->addToCart($product);

        return $this->successResponse();
    }

    public function removeFromCart(Product $product): JsonResponse
    {
        $this->cartService->removeFromCart($product);

        return $this->successResponse();
    }

    public function submitCart(): JsonResponse
    {
        $order = $this->cartService->submitCart();

        return $this->successResponse(data: $order);
    }
}
