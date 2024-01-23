<?php

namespace App\Services\Cart;

use App\Events\ProductAddedToCart;
use App\Events\ProductRemovedFromCart;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\CartNotFoundException;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartService
{
    public function __construct(private readonly CartRepository $cartRepository)
    {
    }

    /**
     * @return void
     */
    public function addToCart(Product $product)
    {
        $user = auth()->user();
        $cart = $this->cartRepository->getActiveCart($user);

        if (! $cart) {
            $cart = $this->cartRepository->createActiveCart($user);
        }

        $this->cartRepository->incrementOrCreateCartItem($cart, $product);

        ProductAddedToCart::dispatch($cart, $product);
    }

    /**
     * @throws CartNotFoundException
     * @throws CartItemNotFoundException
     */
    public function removeFromCart(Product $product)
    {
        $user = auth()->user();
        $cart = $this->cartRepository->getActiveCart($user);

        if (! $cart) {
            throw new CartNotFoundException();
        }

        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity > 1 ? $cartItem->update(['quantity' => $cartItem->quantity - 1]) : $cartItem->delete();

            ProductRemovedFromCart::dispatch($cart, $product);
        } else {
            throw new CartItemNotFoundException();
        }
    }

    /**
     * @throws CartNotFoundException
     */
    public function submitCart()
    {
        $user = auth()->user();

        $cart = $this->cartRepository->getActiveCart($user);

        if (! $cart) {
            throw new CartNotFoundException();
        }
        DB::beginTransaction();
        try {
            // Update cart status to finalized
            $this->cartRepository->makeCartStale($cart);

            $orderService = app(OrderService::class);

            $order = $orderService->createOrder($user, $cart->cartItems);

            $this->cartRepository->createActiveCart($user);

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
