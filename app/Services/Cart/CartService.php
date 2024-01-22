<?php

namespace App\Services\Cart;

use App\Enums\CartState;
use App\Events\ProductAddedToCart;
use App\Events\ProductRemovedFromCart;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\CartNotFoundException;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;

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
            $cart = Cart::create(['user_id' => $user->id, 'is_active' => CartState::Active]);
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
}
