<?php

namespace App\Services;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailNotifierService
{
    public function notify(Order $order, User $admin): void
    {
        Mail::to($admin->email)->send(new OrderPlacedMail($order));
    }
}
