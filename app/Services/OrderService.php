<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;

class OrderService
{
    public function __construct(public OrderRepository $orderRepository)
    {
    }

    public function createOrder($data): Order
    {
        return $this->orderRepository->createOrder($data);
    }

    public function createOrderItemsFromCart(Order $order, $cartItems)
    {
        $this->orderRepository->createOrderItems($order, $cartItems);

        OrderPlaced::dispatch($order);
    }
}
