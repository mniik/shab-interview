<?php

namespace App\Listeners;

use App\Events\ProductAddedToCart;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogForProductAddedToCart implements ShouldQueue
{
    public string $queue = 'log';

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
    public function handle(ProductAddedToCart $event): void
    {
        // log ProductAddedToCart in specific manner
    }
}
