<?php

namespace App\Events;

use App\Models\Moto;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MotoLockedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(protected Moto $moto, protected $numberOrderIssue)
    {
        $this->message = __('alert.order_issue', ['quantity' => $numberOrderIssue, 'moto_name' => $moto->name]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Admin.Orders'),
        ];
    }

    public function broadcastWhen(): bool
    {
        return $this->numberOrderIssue > 0;
    }

    public function broadcastAs()
    {
        return 'admin-issue-orders';
    }
}
