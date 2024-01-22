<?php

namespace App\Events;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductRemovedFromCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Cart $cart;

    protected Product $product;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(Cart $cart, Product $product): void
    {
        $this->cart = $cart;
        $this->product = $product;
    }
}
