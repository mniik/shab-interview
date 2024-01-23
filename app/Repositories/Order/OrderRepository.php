<?php

namespace App\Repositories\Order;

use App\Models\Order;

class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function updateOrderPrice(Order $order, int $price): Order
    {
        $order->total_price = $price;
        $order->save();

        return $order;
    }

    public function createOrderItems(Order $order, $items)
    {
        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'order_id' => $order->id,
            ]);
        }

        return $order->items;
    }
}
