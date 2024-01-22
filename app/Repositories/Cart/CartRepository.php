<?php

namespace App\Repositories\Cart;

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
