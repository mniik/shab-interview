<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\EmailNotifierService;

class EmailToAdminForOrderPlaced
{
    /**
     * Create the event listener.
     */
    public function __construct(private EmailNotifierService $emailNotifierService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $this->emailNotifierService->notify($event->order, $event->admin);
    }
}
