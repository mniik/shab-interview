<?php

namespace App\Listeners;

use App\Events\ProductRemovedFromCart;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogForProductRemovedFromCart implements ShouldQueue
{
    //    public string $queue = 'log';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductRemovedFromCart $event): void
    {
        // log ProductRemovedFromCart in specific manner
    }
}
