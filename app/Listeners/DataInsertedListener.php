<?php

namespace App\Listeners;

use App\Events\DataEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DataInsertedListener
{
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
    public function handle(DataEvent $event): void
    {
        printf($event->permiso);
    }
}
