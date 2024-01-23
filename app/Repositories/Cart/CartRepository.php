<?php

namespace App\Repositories\Cart;

use App\Enums\CartState;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CartRepository
{
    public function getActiveCart(User $user): ?Cart
    {
        return $user->cart()->active()->with('cartItems')->first();
    }

    public function createActiveCart(User $user)
    {
        return Cart::create(['user_id' => $user->id, 'is_active' => CartState::Active]);
    }

    public function makeCartStale(Cart $cart)
    {
        return $cart->update(['is_active' => CartState::Stale]);
    }

    public function incrementOrCreateCartItem(Cart $cart, Product $product): Model
    {
        return $cart->cartItems()->updateOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ], [
            'quantity' => \DB::raw('quantity + 1'),
        ]);
    }
}
