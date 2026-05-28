<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BasketCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public int $basketId,
        public float $totalSum
    )
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("basket.{$this->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'basket.completed';
    }

    public function broadcastWith(): array
    {
        return [
            'basket_id' => $this->basketId,
            'total_sum' => $this->totalSum,
        ];
    }
}
