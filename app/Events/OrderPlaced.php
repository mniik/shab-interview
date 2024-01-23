<?php

namespace App\Events;

use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $admin;

    /**
     * Create a new event instance.
     */
    public function __construct(public Order $order)
    {
        $this->admin = User::where('email', 'admin@admin.com')->first();

    }
}
