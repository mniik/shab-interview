<?php

namespace App\Services\Order;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;

class OrderService
{
    public function __construct(public OrderRepository $orderRepository)
    {
    }

    public function createOrder($user, $cartItems): Order
    {
        $order = $this->orderRepository->createOrder(['user_id' => $user->id]);

        $orderItems = $this->createOrderItemsFromCart($order, $cartItems);
        $totalPrice = $this->getFinalPrice($orderItems);
        $order = $this->orderRepository->updateOrderPrice($order, $totalPrice);

        OrderPlaced::dispatch($order);

        return $order;
    }

    private function createOrderItemsFromCart(Order $order, $cartItems)
    {
        return $this->orderRepository->createOrderItems($order, $cartItems);
    }

    private function getFinalPrice(mixed $orderItems)
    {
        $totalPrice = 0;
        foreach ($orderItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        return $totalPrice;
    }
}
