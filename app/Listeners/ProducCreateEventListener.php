<?php

namespace App\Listeners;

use App\Events\ProductCreateEvent;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProducCreateEventListener
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
    public function handle(ProductCreateEvent $event): void
    {
        Notification::query()->create([
            'title'=>$event->title,
            'message'=>$event->message,
            'user_id'=>$event->user->id
        ]);
    }
}
