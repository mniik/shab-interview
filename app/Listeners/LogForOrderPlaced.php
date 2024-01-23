<?php

namespace App\Listeners;

use App\Events\OrderPlaced;

class LogForOrderPlaced
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
    public function handle(OrderPlaced $event): void
    {
        // log ProductAddedToCart in specific manner

    }
}
