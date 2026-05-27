<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BasketUpdateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public int $totalItems,
        public float $totalSum,
        public ?array $items = null
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("basket.{$this->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'basket.updated';
    }

    public function broadcastWith(): array
    {
        $data = [
            'total_items' => $this->totalItems,
            'total_sum'   => $this->totalSum,
        ];

        if ($this->items !== null) {
            $data['items'] = $this->items;
        }

        return $data;
    }
}
