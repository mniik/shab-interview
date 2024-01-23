<?php

namespace App\Services;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailNotifierService
{
    public function notify(Order $order, User $user): void
    {
        Mail::to($user->email)->send(new OrderPlacedMail($order));
    }
}
