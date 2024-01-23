<?php

namespace App\Repositories\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            return Order::create($data);
        });
    }

    public function createOrderItems(Order $order, $items): void
    {
        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'order_id' => $order->id,
            ]);
        }
    }
}
